<?php
/**
 * - UrlShortenerTest.php
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

        /** @var \Doctrine\DBAL\Connection $db */
        $service = new UrlShortener($db);

        $this->assertEquals(1, $service->create('http://example.com'));
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
        $expected = array(
            'id'      => 1,
            'url'     => 'http://example.org',
            'created' => '2015-01-01 00:00:00'
        );

        $db = $this->getMockConnection();

        $db->expects($this->once())
            ->method('fetchAssoc')
            ->will($this->returnValue($expected));

        /** @var \Doctrine\DBAL\Connection $db */
        $service = new UrlShortener($db);

        $this->assertEquals($expected, $service->find(1));
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

        /** @var \Doctrine\DBAL\Connection $db */
        $service = new UrlShortener($db);

        $this->assertEquals(1, $service->registerClick(1, 'Netscape Navigator 1.0'));
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getMockConnection()
    {
        $db = $this->getMockBuilder('Doctrine\DBAL\Connection')->disableOriginalConstructor()->getMock();

        return $db;
    }
}
