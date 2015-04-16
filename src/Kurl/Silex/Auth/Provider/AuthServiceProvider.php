<?php
/**
 * - AuthServiceProvider.php
 *
 * @author chris
 * @created 23/03/15 09:39
 */

namespace Kurl\Silex\Auth\Provider;

use Gigablah\Silex\OAuth\OAuthServiceProvider;
use Gigablah\Silex\OAuth\Security\User\Provider\OAuthInMemoryUserProvider;
use Silex\Application;
use Silex\Provider\SecurityServiceProvider;
use Silex\ServiceProviderInterface;
use Symfony\Component\HttpFoundation\Request;

class AuthServiceProvider implements ServiceProviderInterface
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
        $app->register(new SecurityServiceProvider(), array(
            'security.firewalls' => array(
                'default' => array(
                    'pattern' => '^/',
                    'anonymous' => true,
                    'oauth' => array(
                        //'login_path' => '/auth/{service}',
                        //'callback_path' => '/auth/{service}/callback',
                        //'check_path' => '/auth/{service}/check',
                        'failure_path' => '/login',
                        'with_csrf' => true
                    ),
                    'logout' => array(
                        'logout_path' => '/logout',
                        'with_csrf' => true
                    ),
                    'users' => new OAuthInMemoryUserProvider()
                )
            ),
            'security.access_rules' => array(
                array('^/auth', 'ROLE_USER')
            )
        ));

        $app->get('/login', function () use ($app) {
            $services = array_keys($app['oauth.services']);

            return $app['twig']->render('auth/index.html.twig', array(
                'login_paths' => array_map(function ($service) use ($app) {
                    return $app['url_generator']->generate('_auth_service', array(
                        'service' => $service,
                        '_csrf_token' => $app['form.csrf_provider']->generateCsrfToken('oauth')
                    ));
                }, array_combine($services, $services))
            ));
        })->bind('login');

        $app->match('/logout', function () use ($app) { })->bind('logout');

        $app->before(function (Request $request) use ($app) {
            $token = $app['security']->getToken();
            $app['user'] = null;

            if ($token && !$app['security.trust_resolver']->isAnonymous($token)) {
                $app['user'] = $token->getUser();
            }
        });
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
        $app->register(new OAuthServiceProvider(), array(
            'oauth.services' => array(
//        'facebook' => array(
//            'key' => FACEBOOK_API_KEY,
//            'secret' => FACEBOOK_API_SECRET,
//            'scope' => array('email'),
//            'user_endpoint' => 'https://graph.facebook.com/me'
//        ),
//        'twitter' => array(
//            'key' => TWITTER_API_KEY,
//            'secret' => TWITTER_API_SECRET,
//            'scope' => array(),
//            'user_endpoint' => 'https://api.twitter.com/1.1/account/verify_credentials.json'
//        ),
                'google' => array(
                    'key' => $app['oauth.services.google.key'],
                    'secret' => $app['oauth.services.google.secret'],
                    'scope' => array(
                        'https://www.googleapis.com/auth/userinfo.email',
                        'https://www.googleapis.com/auth/userinfo.profile'
                    ),
                    'user_endpoint' => 'https://www.googleapis.com/oauth2/v1/userinfo'
                )
            )
        ));
    }
}