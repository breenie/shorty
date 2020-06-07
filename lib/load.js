const DocumentClient = require("aws-sdk").DynamoDB.DocumentClient;
const fs = require("fs");

const tableName = "shorty-development-LinkTable-1B42929DQZ80Y";

const client = new DocumentClient({ region: "eu-west-1" });

console.log("Importing links into DynamoDB. Please wait.");

const urls = JSON.parse(fs.readFileSync(__dirname + "/../shorty.json", "utf8"))[
  "results"
];
urls.forEach(function (url) {
  const params = {
    TableName: tableName,
    Item: {
      id: url.hash,
      url: url.long_url,
      visits: url.clicks,
      owner: "nobody@example.org",
      timestamp: url.created,
    },
  };

  client
    .put(params)
    .promise()
    .then(data => {
      console.log("PutItem succeeded:", data);
    })
    .catch(err => {
      console.error(
        "Unable to add url",
        url.hash,
        ". Error JSON:",
        err.message
      );
    });
});
