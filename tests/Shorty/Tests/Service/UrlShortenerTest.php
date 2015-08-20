<?php
/**
 * Exercises the short URL service.
 *
 * @author  chris
 * @created 10/03/15 17:45
 */
namespace Shorty\Tests\Service;

use Shorty\Service\UrlShortener;
use Silex\Application;

class UrlShortenerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * default expected values for a short URL.
     *
     * @var array
     */
    protected $expected = array(
        'id'      => 1,
        'url'     => 'http://example.org',
        'clicks'  => 50, // This makes little sense for newly created URLS but shouldn't cause any real harm.
        'created' => '2015-01-01T00:00:00+00:00'
    );

    /**
     * Tests creating a new short URL.
     */
    public function testCreateUrl()
    {
        $db = $this->getMockConnection();

        $db->expects($this->once())
            ->method('insert')
            ->will($this->returnValue(1));

        $db->expects($this->once())
            ->method('lastInsertId')
            ->will($this->returnValue(1));

        $db->expects($this->once())
            ->method('fetchAssoc')
            ->will($this->returnValue($this->expected));

        /** @var \Doctrine\DBAL\Connection $db */
        $service = new UrlShortener($db);

        $this->assertEquals($this->expected, $service->create('http://example.com')->jsonSerialize());
    }

    /**
     * Ensure we get back null when a URL can't be found by Id.
     */
    public function testFindUrlNotFound()
    {
        $db = $this->getMockConnection();

        $db->expects($this->once())
            ->method('fetchAssoc')
            ->will($this->returnValue(null));

        /** @var \Doctrine\DBAL\Connection $db */
        $service = new UrlShortener($db);

        $this->assertNull($service->find(1));
    }

    /**
     * Tests successful gathering of a URL.
     */
    public function testFindUrl()
    {
        $db = $this->getMockConnection();

        $db->expects($this->once())
            ->method('fetchAssoc')
            ->will($this->returnValue($this->expected));

        /** @var \Doctrine\DBAL\Connection $db */
        $service = new UrlShortener($db);

        $result = $service->find(1);

        $this->assertInstanceOf('Shorty\Model\ShortyUrl', $result);
        $this->assertEquals($this->expected, $result->jsonSerialize());
    }

    /**
     * Exercises the pagination.
     */
    public function testPaginate()
    {
        $db = $this->getMockConnection();

        $mockStatement = $this->getMockBuilder('Doctrine\DBAL\Driver\Statement')->disableOriginalConstructor()->getMock();
        $mockStatement->expects($this->any())->method('bindValue');
        $mockStatement->expects($this->once())->method('execute');
        $mockStatement->expects($this->once())
            ->method('fetchAll')
            ->will(
                $this->returnValue(
                    json_decode(file_get_contents(__DIR__ . '/Fixtures/get-urls.json'), true)
                )
            );

        $db->expects($this->once())->method('prepare')->will($this->returnValue($mockStatement));

        $mockCount = $this->getMockBuilder('Doctrine\DBAL\Driver\Statement')->disableOriginalConstructor()->getMock();
        $mockCount->expects($this->once())->method('fetch')->willReturn(527);
        $db->expects($this->once())->method('query')->will($this->returnValue($mockCount));

        /** @var \Doctrine\DBAL\Connection $db */
        $service = new UrlShortener($db);

        $urls = $service->paginate();

        $this->assertArrayHasKey('total', $urls);
        $this->assertArrayHasKey('results', $urls);
        $this->assertEquals(10, count($urls['results']));
    }

    /**
     * Tests click logging.
     */
    public function testRegisterClick()
    {
        $db = $this->getMockConnection();

        $db->expects($this->once())
            ->method('insert')
            ->will($this->returnValue(1));

        $db->expects($this->once())
            ->method('fetchAssoc')
            ->will($this->returnValue($this->expected));

        /** @var \Doctrine\DBAL\Connection $db */
        $service = new UrlShortener($db);

        $this->assertEquals(
            '{"id":"1","url":"http:\/\/example.org","clicks":50,"created":"2015-01-01T00:00:00+00:00"}',
            json_encode($service->registerClick(1, 'Netscape Navigator 1.0'))
        );
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getMockConnection()
    {
        return $this->getMockBuilder('Doctrine\DBAL\Connection')->disableOriginalConstructor()->getMock();
    }
}
