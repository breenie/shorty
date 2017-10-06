const express = require('express');
const app     = express();

const options = {
  dsn: process.env[process.env.DSN_SOURCE_ENV_NAME || 'JAWSDB_MARIA_URL'],
  port: process.env.PORT || 5000
};

const connection = require('./src/connection')(options.dsn);

app.use(express.static(__dirname + '/web'));
app.use('/', require('./routes/html'));
app.use('/api/urls', require('./routes/urls')(connection));

app.listen(options.port, () => {
  console.log('Shorty is running on port', options.port);
});
