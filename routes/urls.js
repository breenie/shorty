const express   = require('express');
const router    = express.Router();
const SqlString = require('sqlstring');
const base62    = require('base62');

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

module.exports = (connection) => {
  router.get('/', function (request, response) {
    const direction = 'desc';

    connection.then((c) => {
      const query = SqlString.format(
        'select SQL_CALC_FOUND_ROWS u.id, u.url, u.created, count(v.shorty_url_id) as clicks ' +
        'from shorty_url u ' +
        'left join shorty_url_visit v on v.shorty_url_id = u.id ' +
        'group by u.id ' +
        'order by u.id ? ' +
        'limit ? offset ?',
        [
          SqlString.raw(direction),
          Number.parseInt(request.query.limit || 10),
          Number.parseInt(request.query.offset || 0)
        ]
      );

      c.query(query)
        .then((rows) => {
          c.query('select found_rows() as total').then((total) => {
            response.json({
              results: rows.map((row) => {
                return serialize(row, request.protocol + '://' + request.get('host'));
              }),
              total:   total[0]['total']
            });
          });
        });
    });
  });

  router.get('/:id', (request, response) => {
    connection.then((c) => {
      const id = base62.decode(request.params.id);
      const query = SqlString.format(
        'select u.id, u.url, u.created, count(v.shorty_url_id) as clicks ' +
        'from shorty_url u ' +
        'left join shorty_url_visit v on v.shorty_url_id = u.id ' +
        'where u.id = ? ' +
        'group by u.id',
        [id]
      );

      c.query(query)
        .then((rows) => {
          if (1 === rows.length) {
            response.json(serialize(rows[0], request.protocol + '://' + request.get('host')));
          }
        });
    });
  });

  return router;
};