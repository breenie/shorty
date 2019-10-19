const express = require('express');
const router = express.Router();

module.exports = function (service) {
  router.get('/:id', (request, response) => {
    service.registerClick(request.params.id, request.headers['User-Agent'] || 'Unknown')
      .then((url) => {
        response.redirect(301, url.url);
      })
      .catch(e => {
        response
          .status(404)
          .send(e.message + ' ' + request.params.id || 'Unknown error')
      });
  });

  return router;
};