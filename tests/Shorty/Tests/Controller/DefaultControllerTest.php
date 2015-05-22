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
//        $client  = $this->createClient();
//        $crawler = $client->request('GET', '/');
//
//        $this->assertTrue($client->getResponse()->isOk());
//        $this->assertCount(1, $crawler->filter('h1:contains("Shorten really long URLs")'));
//        $this->assertCount(1, $crawler->filter('form'));
    }

    public function testCreateUrl()
    {
//        $this->app['db']->expects($this->once())
//            ->method('fetchAssoc')
//            ->will(
//                $this->returnValue(array('id' => 1, 'url' => 'http://example.com', 'created' => '2015-01-01 00:00:00'))
//            );
//
//        $client  = $this->createClient();
//        $client->request('GET', '/1');

//        echo $client->getResponse()->getContent();
//        var_dump($client->getResponse()->getStatusCode());

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
