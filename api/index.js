const express = require('express');
const app     = express();
const bodyParser = require('body-parser');

const options = {
  dsn:  process.env[process.env.DSN_SOURCE_ENV_NAME || 'JAWSDB_MARIA_URL'],
  port: process.env.PORT || 5000
};

const UrlService = require('./src/services/urls');
const service    = new UrlService();

app.use(bodyParser.json());
app.use(bodyParser.urlencoded({extended: true}));
app.use('/', require('./src/routes/redirects')(service));
app.use('/api/urls', require('./src/routes/urls')(service));

app.listen(process.env.PORT);
