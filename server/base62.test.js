const assert = require('assert');
const base62 = require('../server/base62');

describe('Base62', function () {
    const known = {
        'Ay': 680,
        'Cc': 782,
        'aY': 2266,
        'cC': 2368
    };

    describe('encode', function () {
        it('should encode known values predictably', function () {
            Object.keys(known).forEach(element => {
                assert.equal(base62.encode(known[element]), element);
            });
        });
    });

    describe('decode', function () {
        it('should decode known values predictably', function () {
            Object.keys(known).forEach(element => {
                assert.equal(base62.decode(element), known[element]);
            });
        });
    });
});