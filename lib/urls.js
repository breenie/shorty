import { DynamoDB } from "aws-sdk";
import { config } from "./config";

const client = new DynamoDB(config.aws);

const format = row => {
  return {
    id: row.id.S,
    url: row.url.S,
    timestamp: row.timestamp.S,
    owner: row.owner.S,
    visits: row.visits.N,
  };
};

export async function list(filter) {
  let params = {
    TableName: config.urlTableName,
    Limit: Number.parseInt(filter.limit || 10),
    Select: "ALL_ATTRIBUTES",
  };

  if (filter.last) {
    params.ExclusiveStartKey = JSON.parse(
      Buffer.from(filter.last, "base64").toString()
    );
  }

  return client
    .scan(params)
    .promise()
    .then(data => {
      let results = {
        urls: data.Items.map(format),
      };

      if (data.LastEvaluatedKey) {
        results.last = Buffer.from(
          JSON.stringify(data.LastEvaluatedKey)
        ).toString("base64");
      }

      return results;
    });
}

export async function visit(id) {
  const params = {
    TableName: config.urlTableName,
    ConditionExpression: "attribute_exists(id)",
    Key: {
      id: {
        S: id,
      },
    },
    ExpressionAttributeNames: {
      "#v": "visits",
    },
    ExpressionAttributeValues: {
      ":v": {
        N: "1",
      },
    },
    UpdateExpression: "SET #v = #v + :v",
    ReturnValues: "ALL_NEW",
  };

  return client
    .updateItem(params)
    .promise()
    .then(result => result.Attributes);
}

export async function create(data) {
  const params = {
    TableName: config.urlTableName,
    ConditionExpression: "attribute_not_exists(id)",
    Key: {
      id: {
        S: data.id,
      },
    },
    ExpressionAttributeNames: {
      "#u": "url",
      "#o": "owner",
      "#ts": "timestamp",
      "#v": "visits",
    },
    ExpressionAttributeValues: {
      ":u": {
        S: data.url,
      },
      ":o": {
        S: "nobody@example.org",
      },
      ":ts": {
        S: new Date().toJSON(),
      },
      ":v": {
        N: "0",
      },
    },
    UpdateExpression: "SET #u = :u, #o = :o, #ts = :ts, #v = :v",
    ReturnValues: "ALL_NEW",
  };

  return client
    .updateItem(params)
    .promise()
    .then(results => results.Attributes)
    .then(format);
}
