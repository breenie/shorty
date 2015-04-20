<?php
/**
 * - ShortUrlServiceProvider.php
 *
 * @author chris
 * @created 02/02/15 10:50
 */

namespace Shorty\Provider;

use Kurl\Maths\Encode\Base62;
use Shorty\Controller\ApiController;
use Shorty\Controller\DefaultController;
use Shorty\Service\UrlShortener;
use Silex\Application;
use Silex\ServiceProviderInterface;

class ShortyServiceProvider implements ServiceProviderInterface
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
        //$app['twig.loader']->addLoader(new \Twig_Loader_Filesystem(__DIR__ . '/../Resources/views'));
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
        $app['kurl.base62'] = $app->share(function() { return new Base62(); });

        // $app['shorty.url_generator']

        $app['kurl.service.url_shortener'] = new UrlShortener($app['db']);
        $app['app.default_controller'] = $app->share(
            function () use ($app) {
                return new DefaultController($app);
            }
        );

        $app['app.api_controller'] = $app->share(
            function () use ($app) {
                return new ApiController($app);
            }
        );

        $app->get('/', 'app.default_controller:indexAction')->bind('kurl_shorty');
        $app->get('/statistics', 'app.default_controller:statisticsAction')->bind('kurl_shorty_statistics');
        $app->post('/', 'app.default_controller:indexAction')->bind('kurl_shorty_create');
        $app->get('/{id}', 'app.default_controller:redirectAction')->bind('kurl_shorty_redirect');
        $app->get('/{id}/details', 'app.default_controller:detailsAction')->bind('kurl_shorty_details');

        $app->get('/api/urls', 'app.api_controller:getLinksAction')->bind('kurl_shorty_api_urls');
    }
}