const env = process.env["NODE_ENV"] || "development";

const development = {
  aws: { region: process.env["REGION"] || "eu-west-1" },
  urlTableName: process.env["URL_TABLE_NAME"],
};

const production = {
  aws: {
    accessKeyId: process.env["ACCESS_KEY_ID"],
    secretAccessKey: process.env["SECRET_ACCESS_KEY"],
    region: process.env["REGION"] || "eu-west-1",
  },
  urlTableName: process.env["URL_TABLE_NAME"],
};

const configs = {
  development,
  production,
};

export const config = configs[env];
