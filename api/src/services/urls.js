const AWS = require("aws-sdk");
AWS.config.update({ region: "eu-west-1" });
const IS_OFFLINE = false;

const shortId = (count = 7) => {
  const chars =
    "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
  return "x".repeat(count).replace(/x/g, c => {
    return chars.charAt((Math.random() * chars.length) | 0);
  });
};

const dynamodb = new AWS.DynamoDB.DocumentClient(
  "true" === IS_OFFLINE
    ? {
        region: "localhost",
        endpoint: "http://localhost:8000"
      }
    : null
);

const URLS_TABLE = "shorty-urls";

const UrlService = function () {
  
}

UrlService.prototype.getUrl = function(id) {
  const params = {
    TableName: URLS_TABLE,
    IndexName: 'id-index',
    KeyConditionExpression: "id = :id",
    Select: 'ALL_ATTRIBUTES',
    ExpressionAttributeValues: {
      ":id": String(id)
    }
  };

  return dynamodb
    .query(params)
    .promise()
    .then(data => {
      // TODO fix this test to filter out too many results
      if (!data.Items[0]) {
        throw new Error("Couldn't find url id " + id);
      }

      return data.Items[0];
    });
};

UrlService.prototype.create = function(url) {
  // TODO add promiseRetry
  const id = shortId(6);
  const params = {
    Item: { id, url, created: (new Date).toISOString(), clicks: 0 },
    TableName: URLS_TABLE
  };

  return dynamodb
    .put(params)
    .promise()
    .then(() => this.getUrl(id));
};

UrlService.prototype.filter = function (filter) {
  let params = {
    TableName: URLS_TABLE,
    Limit: Number.parseInt(filter.limit || 10),
    Select: "ALL_ATTRIBUTES",
    ProjectionExpression: 'id, created'
  };

  if (filter.last) {
    params.ExclusiveStartKey = JSON.parse(filter.last);
  }

  return dynamodb
    .scan(params)
    .promise()
    .then(data => { 
      let results = {
        results: data.Items
      };

      if (data.LastEvaluatedKey) {
        results.last = JSON.stringify(data.LastEvaluatedKey);
      }

      return results;
    });
};

UrlService.prototype.registerClick = function(id, userAgent) {
  const params = {
    Key: { id },
    UpdateExpression: "set #c = #c + :incr",
    ExpressionAttributeNames: { "#c": "clicks" },
    ExpressionAttributeValues: {
      ":incr": 1
    },
    TableName: URLS_TABLE
  };
  return dynamodb
    .update(params)
    .promise()
    .then(data => {
      return this.getUrl(id);
    });
};

module.exports = UrlService;
