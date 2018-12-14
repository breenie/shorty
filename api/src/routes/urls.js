const express = require('express');
const router  = express.Router();
const base62  = require('../base62');

const serialize = (url, host) => {
  const hash = base62.encode(url.id);
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
        total:   result.total
      });
    });
  });

  router.get('/:id', (request, response) => {
    const id = base62.decode(request.params.id);

    service.getUrl(id)
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
    const id = base62.decode(request.params.id);

    service.registerClick(id, request.headers['User-Agent']).then((url) => {
      response.json(serialize(url, request.protocol + '://' + request.get('host')));
    });
  });

  return router;
};