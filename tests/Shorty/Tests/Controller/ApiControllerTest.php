<?php
/**
 * - ApiControllerTest.php
 *
 * @author  chris
 * @created 16/05/15 10:25
 */
namespace Shorty\Tests\Controller;

use Silex\WebTestCase;
use Symfony\Component\HttpKernel\HttpKernel;

class ApiControllerTest extends WebTestCase
{
    /**
     * Stop phpunit complaining.
     */
    public function testNothing()
    {
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
