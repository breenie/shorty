const AWS = require("aws-sdk");
const fs = require("fs");
AWS.config.update({
  region: "eu-west-1"
});

const docClient = new AWS.DynamoDB.DocumentClient();

console.log("Importing movies into DynamoDB. Please wait.");

const urls = JSON.parse(fs.readFileSync("shorty.json", "utf8"))["results"];
urls.forEach(function(url) {
  const params = {
    TableName: "shorty-stage-LinkTable-2ESH74PNJELF",
    Item: {
      id: url.hash,
      url: url.long_url,
      visits: url.clicks,
      owner: "nobody@example.org",
      timestamp: url.created
    }
  };

  docClient
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
