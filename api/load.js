const AWS = require("aws-sdk");
const fs = require('fs');

AWS.config.update({
  region: "eu-west-1"
});

const docClient = new AWS.DynamoDB.DocumentClient();

console.log("Importing movies into DynamoDB. Please wait.");

const urls = JSON.parse(fs.readFileSync('shorty.json', 'utf8'))['results'];
urls.forEach(function(url) {

  const params = {
    TableName: "shorty-urls",
    Item: {
      id: url.hash, url: url.long_url, clicks: url.clicks, created: url.created
    }
  };

  docClient.put(params, function(err, data) {
    if (err) {
      console.error("Unable to add url", url.hash, ". Error JSON:", err.message);
    } else {
      console.log("PutItem succeeded:", url.hash);
    }
  });
});