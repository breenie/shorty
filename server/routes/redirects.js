const express = require('express');
const router  = express.Router();
const base62  = require('../base62');

module.exports = function (service) {
  router.get('/:id', (request, response) => {
    const id = base62.decode(request.params.id);

    service.registerClick(id, request.headers['User-Agent'] || 'Unknown')
      .then((url) => {
        response.redirect(301, url.url);
      })
      .catch(e => {
        response
          .status(404)
          .send(e.message || 'Unknown error')
      });
  });

  return router;
};