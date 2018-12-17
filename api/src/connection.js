const mysql     = require('promise-mysql');
const DSNParser = require('dsn-parser')

module.exports = (connectionString) => {
  const dsn = new DSNParser(connectionString);

  return mysql.createConnection(dsn.getParts())
    .catch(e => console.log('Cannot connect to server: ' + e.message));
};