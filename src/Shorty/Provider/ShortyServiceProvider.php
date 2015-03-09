<?php
/**
 * - ShortUrlServiceProvider.php
 *
 * @author chris
 * @created 02/02/15 10:50
 */

namespace Shorty\Provider;

use Kurl\Component\ShortUrl\Form\ShortUrlForm;
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
        $app['twig.loader']->addLoader(new \Twig_Loader_Filesystem(__DIR__ . '/../Resources/views'));
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
        $app['kurl.service.url_shortener'] = new UrlShortener($app['db']);
        $app['app.default_controller'] = $app->share(
            function () use ($app) {
                return new DefaultController($app);
            }
        );

        $app->get('/', 'app.default_controller:indexAction')->bind('kurl_shorty');
        $app->post('/', 'app.default_controller:indexAction')->bind('kurl_shorty_create');
        $app->get('/{id}', 'app.default_controller:redirectAction')->bind('kurl_shorty_redirect');
        $app->get('/{id}/details', 'app.default_controller:detailsAction')->bind('kurl_shorty_details');
    }
}