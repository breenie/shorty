describe('filter', function () {

    beforeEach(module('urlFilters'));

    describe('remove_shcme', function () {

        it('should remove the scheme from a URL',
            inject(function (checkmarkFilter) {
                expect(checkmarkFilter('aregularstring')).toBe('aregularstring');
                expect(checkmarkFilter('http://example.com')).toBe('example.com');
                expect(checkmarkFilter('https://example.com')).toBe('example.com');
                expect(checkmarkFilter('http://example.com/index.html?a=b#c67')).toBe('example.com/index.html?a=b#c67');
                expect(checkmarkFilter('https://example.com/index.html?a=b#c67')).toBe('example.com/index.html?a=b#c67');
            }));
    });
});