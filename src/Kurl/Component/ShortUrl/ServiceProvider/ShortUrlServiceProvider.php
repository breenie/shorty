<?php
/**
 * - ShortUrlServiceProvider.php
 *
 * @author chris
 * @created 02/02/15 10:50
 */

namespace Kurl\Component\ShortUrl\ServiceProvider;

use Kurl\Component\ShortUrl\Controller\DefaultController;
use Kurl\Component\ShortUrl\Form\ShortUrlForm;
use Silex\Application;
use Silex\ServiceProviderInterface;

class ShortUrlServiceProvider implements ServiceProviderInterface
{

    /**
     * Registers services on the given app.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     *
     * @param Application $app
     */
    public function register(Application $app)
    {
    }

    /**
     * Bootstraps the application.
     *
     * This method is called after all services are registered
     * and should be used for "dynamic" configuration (whenever
     * a service must be requested).
     *
     * @param Application $app
     */
    public function boot(Application $app)
    {
        $app['app.default_controller'] = $app->share(
            function () use ($app) {
                return new DefaultController($app['form.factory'], $app['kurl.view.handler'], $app['logger']);
            }
        );

        $app->get('/', 'app.default_controller:indexAction')->bind('homepage');

        $app['form.types'] = $app->share(
            $app->extend(
                'form.types',
                function ($types) use ($app) {
                    return array(new ShortUrlForm());
                }
            )
        );
    }
}