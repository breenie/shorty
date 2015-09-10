(function (){
  'use strict';

  describe('Strip URL scheme filter', function () {
    beforeEach(module('shortyApp'));

    it('Should strip the scheme from a URL', inject(function(stripSchemeFilter) {
      expect(stripSchemeFilter('http://example.com/foo/bar?one=2')).toBe('example.com/foo/bar?one=2');
      expect(stripSchemeFilter('http://example.com/')).toBe('example.com/');
      expect(stripSchemeFilter('https://example.com/')).toBe('example.com/');
      expect(stripSchemeFilter('ftp://example.com/')).toBe('example.com/');
      expect(stripSchemeFilter('example.com')).toBe('example.com');
      expect(stripSchemeFilter('')).toBe('');
      expect(stripSchemeFilter(undefined)).toBe('');
    }));
  });
})();