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
