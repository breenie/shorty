<?php
/**
 * Provides the Shorty controllers and services.
 *
 * @author  chris
 * @created 02/02/15 10:50
 */

namespace Shorty\Provider;

use Kurl\Maths\Encode\Base62;
use Shorty\Controller\ApiController;
use Shorty\Controller\DefaultController;
use Shorty\Service\UrlShortener;
use Silex\Application;
use Silex\ServiceProviderInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Validator\Constraints as Assert;

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
        $app['kurl.base62'] = $app->share(
            function () {
                return new Base62();
            }
        );

        $app['kurl.service.url_shortener'] = $app->share(
            function ($app) {
                return new UrlShortener($app['db']);
            }
        );

        $app['shorty.form.create_url'] = $app->share(
            function(Application $app) {
                return $app['form.factory']->createBuilder('form', null, ['csrf_protection' => false])
                    ->setMethod('POST')
                    ->add(
                        'url',
                        'url',
                        ['required' => true, 'constraints' => [new Assert\NotBlank(), new Assert\Url()]]
                    )
                    ->getForm();
            }
        );
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
                return new DefaultController($app);
            }
        );

        $app['app.api_controller'] = $app->share(
            function () use ($app) {
                return new ApiController($app);
            }
        );

        // TODO rename these routes.
        $app->get('/', 'app.default_controller:indexAction')->bind('kurl_shorty');
        $app->get('/statistics', 'app.default_controller:statisticsAction')->bind('kurl_shorty_statistics');
        //$app->post('/', 'app.default_controller:indexAction')->bind('kurl_shorty_create');
        $app->get('/{id}', 'app.default_controller:redirectAction')->bind('kurl_shorty_redirect');
        $app->get('/{id}/details', 'app.default_controller:detailsAction')->bind('kurl_shorty_details');

        $app->get('/api/urls.json', 'app.api_controller:getLinksAction')->bind('kurl_shorty_api_urls');
        $app->get('/api/urls/{id}.json', 'app.api_controller:getLinkAction')->bind('kurl_shorty_api_url');
        $app->post('/api/urls.json', 'app.api_controller:postLinkAction')->bind('kurl_shorty_api_post');
        $app->patch('/api/urls/{id}/clicks.json', 'app.api_controller:patchLinkClicksAction')->bind(
            'kurl_shorty_api_patch_clicks'
        );

        $app->before(
            function (Request $request) {
                if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
                    $data = json_decode($request->getContent(), true);

                    if (JSON_ERROR_NONE === json_last_error()) {
                        $request->request->replace(is_array($data) ? $data : array());
                    } else {
                        throw new HttpException(
                            Response::HTTP_BAD_REQUEST,
                            sprintf('Could not decode JSON body. %1$s', json_last_error_msg())
                        );
                    }
                }
            }
        );

        /** @noinspection PhpUnusedParameterInspection */
        $app->after(
            function (Request $request, Response $response, Application $app) {

                // Other tests for request method are handled downstream so just set the length whatever.
                if (true === $response instanceof JsonResponse) {
                    $response->headers->set('Content-Length', strlen($response->getContent()), true);
                }
            }
        );
    }
}