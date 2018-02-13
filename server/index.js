const express = require('express');
const app     = express();
const bodyParser = require('body-parser');

const options = {
  dsn:  process.env[process.env.DSN_SOURCE_ENV_NAME || 'JAWSDB_MARIA_URL'],
  port: process.env.PORT || 5000
};

const connection = require('./connection')(options.dsn);
const UrlService = require('./services/urls');
const service    = new UrlService(connection);

app.use(bodyParser.json());
app.use(bodyParser.urlencoded({extended: true}));
app.use(express.static(__dirname + '/../dist'));
app.use('/', require('./routes/html'));
app.use('/', require('./routes/redirects')(service));
app.use('/api/urls', require('./routes/urls')(service));

app.listen(options.port, () => {
  console.log('Shorty is running on port', options.port);
});