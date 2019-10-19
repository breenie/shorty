const express = require('express');
const router  = express.Router();

const serialize = (url, host) => {
  const hash = url.id;
  return {
    hash:      hash,
    short_url: host + '/' + hash,
    long_url:  url.url,
    clicks:    url.clicks,
    created:   url.created
  }
};

module.exports = function (service) {
  router.get('/', function (request, response) {
    service.filter(request.query).then((result) => {
      response.json({
        results: result.results.map((row) => {
          return serialize(row, request.protocol + '://' + request.get('host'));
        }),
        last:   result.last
      });
    });
  });

  router.get('/:id', (request, response) => {
    service.getUrl(request.params.id)
      .then((url) => {
        response.json(serialize(url, request.protocol + '://' + request.get('host')));
      })
      .catch(e => {
        response.status(404).json({error: (e.message + ' ' + request.params.id) || 'Unknown error'})
      });
  });

  router.post('/', (request, response) => {
    service.create(request.body.form.url).then((url) => {
      const result = serialize(url, request.protocol + '://' + request.get('host'));
      response
        .status(201)
        .set('Location', result.short_url)
        .json(result);
    });
  });

  router.patch('/:id/clicks', (request, response) => {
    service.registerClick(request.params.id, request.headers['User-Agent']).then((url) => {
      response.json(serialize(url, request.protocol + '://' + request.get('host')));
    });
  });

  return router;
};