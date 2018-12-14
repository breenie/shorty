'use strict';

const SqlString = require('sqlstring');

const UrlService = function (connection) {
  this.connection = connection;
};

UrlService.prototype.getUrl = function (id) {
  return this.connection.then((c) => {
    const query = SqlString.format(
      'select u.id, u.url, u.created, count(v.shorty_url_id) as clicks ' +
      'from shorty_url u ' +
      'left join shorty_url_visit v on v.shorty_url_id = u.id ' +
      'where u.id = ? ' +
      'group by u.id',
      [id]
    );

    return c.query(query)
      .then((rows) => {
        return new Promise((resolve, reject) => {
          if (1 === rows.length) {
            resolve(rows[0]);
          } else {
            reject(new Error('Couldn\'t find url id ' + id));
          }
        });
      });
  });
};

UrlService.prototype.create = function (url) {
  return this.connection.then((connection) => {
    return connection.query(
      'insert into shorty_url values (null, ?, CURRENT_TIMESTAMP)',
      [url]
    ).then(() => {
      return connection.query('select last_insert_id() as last_insert_id').then((rows) => {
        return this.getUrl(rows[0]['last_insert_id']);
      })
    });
  });
};

UrlService.prototype.filter = function (filter) {
  return this.connection.then((connection) => {
    const query = SqlString.format(
      'select SQL_CALC_FOUND_ROWS u.id, u.url, u.created, count(v.shorty_url_id) as clicks ' +
      'from shorty_url u ' +
      'left join shorty_url_visit v on v.shorty_url_id = u.id ' +
      'group by u.id ' +
      'order by u.id ? ' +
      'limit ? offset ?',
      [
        SqlString.raw(filter.direction || 'desc'),
        Number.parseInt(filter.limit || 10),
        Number.parseInt(filter.offset || 0)
      ]
    );

    return connection.query(query)
      .then((rows) => {
        return connection.query('select found_rows() as total').then((total) => {
          return new Promise((resolve) => {
            resolve({
              results: rows,
              total:   total[0]['total']
            });
          });
        });
      });
  });
};

UrlService.prototype.registerClick = function (id, userAgent) {
  return this.connection.then((connection) => {
    connection.query(
      'insert into shorty_url_visit values (?, ?, CURRENT_TIMESTAMP)',
      [id, userAgent]
    );

    return this.getUrl(id);
  });
};

module.exports = UrlService;