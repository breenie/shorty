const AWS = require('aws-sdk');
AWS.config.update({region: 'eu-west-1'});
const IS_OFFLINE = false;

const dynamodb = new AWS.DynamoDB.DocumentClient(
  'true' === IS_OFFLINE ? {
    region: 'localhost',
    endpoint: 'http://localhost:8000'
  } : null
);

const URLS_TABLE = 'shorty-urls';

const SqlString = require('sqlstring');

const UrlService = function (connection) {
  this.connection = connection;
};

UrlService.prototype.getUrl = function (id) {
  return new Promise((resolve, reject) => {
    const params = {
      TableName: URLS_TABLE,
      IndexName: 'IdIndex',
      KeyConditionExpression: "id = :id",
      Select: 'ALL_ATTRIBUTES',
      ExpressionAttributeValues: {
        ":id": String(id)
      }
    };

    // TODO convert this to GetItem and update IAM policy
    dynamodb.query(params, function (err, data) {
      if (err) {
        reject(err); // an error occurred
      }

      // TODO check why this returns null, I'd expect "Items" to exist.
      if (data && data.Items && data.Count && 1 === data.Count) {
        resolve(data.Items[0]);
      } else {
        reject(new Error('Couldn\'t find url id ' + id));
      }
    });
  });
};

UrlService.prototype.create = function (url) {
  return this.connection.then((connection) => {
    return connection.query(
      'insert into shorty_url values (null, ?, CURRENT_TIMESTAMP)',
      [url]
    ).then(() => {
      return connection.query('select last_insert_id() as last_insert_id').then((rows) => {
        return this.getUrl(rows[0]['last_insert_id']);
      })
    });
  });
};

UrlService.prototype.filter = function (filter) {
  return new Promise((resolve, reject) => {
    let params = {
      TableName: URLS_TABLE,
      Limit: Number.parseInt(filter.limit || 10),
      Select: 'ALL_ATTRIBUTES'
    };

    if (filter.last) {
      params.ExclusiveStartKey = JSON.parse(filter.last)
    }

    dynamodb.scan(params, function (err, data) {
      if (err) {
        return reject(err);
      }

       let results = {
        results: data.Items
      };

      if (data.LastEvaluatedKey) {
        results.last = data.LastEvaluatedKey;
      }

      resolve(results);
    });
  });
};

UrlService.prototype.registerClick = function (id, userAgent) {
  return this.connection.then((connection) => {
    connection.query(
      'insert into shorty_url_visit values (?, ?, CURRENT_TIMESTAMP)',
      [id, userAgent]
    );

    return this.getUrl(id);
  });
};

module.exports = UrlService;