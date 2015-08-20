(function (){
  'use strict';

  describe('Capitalise string filter', function () {
    beforeEach(module('shortyApp'));

    it('Should capitalise the first letter of a string', inject(function(capitaliseFilter) {
      expect(capitaliseFilter('foobar')).toBe('Foobar');
      expect(capitaliseFilter('foo Bar')).toBe('Foo bar');
      expect(capitaliseFilter('FOO BAR')).toBe('Foo bar');
      expect(capitaliseFilter('FOOBAR!')).toBe('Foobar!');
      expect(capitaliseFilter('')).toBe('');
      expect(capitaliseFilter(undefined)).toBe('');
    }));
  });
})();