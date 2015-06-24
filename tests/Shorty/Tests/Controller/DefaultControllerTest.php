<?php
/**
 * - DefaultControllerTest.php
 *
 * @author  chris
 * @created 10/03/15 17:36
 */
namespace Shorty\Tests\Controller;

use Silex\WebTestCase;
use Symfony\Component\HttpKernel\HttpKernel;

class DefaultControllerTest extends WebTestCase
{
    /**
     * Make sure the default page renders OK.
     */
    public function testIndexPage()
    {
        $client  = $this->createClient();
        $crawler = $client->request('GET', '/');

        $this->assertTrue($client->getResponse()->isOk());
        //$this->assertCount(1, $crawler->filter('h1:contains("Shorten really long URLs")'));
        //$this->assertCount(1, $crawler->filter('form'));
    }

    /**
     * Tests the stats page.
     */
    public function testGetStatistics()
    {
        $client  = $this->createClient();
        $crawler = $client->request('GET', '/statistics');

        $this->assertTrue($client->getResponse()->isOk());
        //$this->assertCount(1, $crawler->filter('h1:contains("Boring statistics")'));
        //$this->assertCount(1, $crawler->filter('table'));
    }

    /**
     * Tests the stats page.
     */
    public function testGetSinglePage()
    {
        $client  = $this->createClient();
        $crawler = $client->request('GET', '/8f/details');

        $this->assertTrue($client->getResponse()->isOk());
        //$this->assertCount(1, $crawler->filter('h1:contains("Details for short link")'));
        //$this->assertCount(1, $crawler->filter('table'));
    }


    /**
     * Ensures we get a redirect.
     */
    public function testRedirect()
    {
        $redirect = 'http://example.com';
        $this->app['db']->expects($this->once())
            ->method('fetchAssoc')
            ->will(
                $this->returnValue(array('id' => 1, 'url' => $redirect, 'clicks' => 0, 'created' => '2015-01-01 00:00:00'))
            );

        $client  = $this->createClient();
        $client->request('GET', '/1');

        $this->assertTrue($client->getResponse()->isRedirect($redirect));
    }

    /**
     * Creates the application.
     *
     * @return HttpKernel
     */
    public function createApplication()
    {
        return require __DIR__ . '/../../../bootstrap.php';
    }
}
