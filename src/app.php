<?php

use Silex\Application;
use Silex\Provider\FormServiceProvider;
use Silex\Provider\MonologServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;
use Silex\Provider\ValidatorServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;

$app = new Application();

$app['debug'] = filter_var(getenv('DEBUG'), FILTER_VALIDATE_BOOLEAN);
$app['env'] = getenv('SYMFONY_ENV') ?: 'prod';

if (true === $app['debug']) {
    Symfony\Component\Debug\Debug::enable(E_ALL);
} else {
    // @codeCoverageIgnoreStart
    error_reporting(E_ERROR);
    // @codeCoverageIgnoreEnd
}

$app['monolog.logfile'] = __DIR__ . '/../var/logs/silex_' . $app['env'] . '.log';
$app['asset_path']      = '/components';
$app['db.options']      = array(
    'driver'   => getenv('DB_DRIVER') ?: 'pdo_mysql', // currently only mysql is supported
    'dbname'   => getenv('DB_NAME'),
    'host'     => getenv('DB_HOST'),
    'port'     => getenv('DB_PORT'),
    'user'     => getenv('DB_USER'),
    'password' => getenv('DB_PASS'),
);

$app['oauth.services.google.key'] = getenv('OAUTH_SERVICES_GOOGLE_KEY');
$app['oauth.services.google.secret'] = getenv('OAUTH_SERVICES_GOOGLE_SECRET');

$app->register(new UrlGeneratorServiceProvider());
$app->register(new ValidatorServiceProvider());
$app->register(new ServiceControllerServiceProvider());
$app->register(new TwigServiceProvider());
//$app['twig'] = $app->share($app->extend('twig', function ($twig, $app) {
//    // add custom globals, filters, tags, ...
//
//    return $twig;
//}));

$app['twig.path']       = array(
    __DIR__ . '/../src/Shorty/Resources/views',
    __DIR__ . '/../src/Kurl/Silex/Auth/Resources/views',
);

$app['twig.options'] = array('cache' => __DIR__ . '/../var/cache/twig');

$app->register(new FormServiceProvider());
$app->register(new Silex\Provider\SessionServiceProvider());
$app->register(new MonologServiceProvider());
$app->register(new Silex\Provider\DoctrineServiceProvider(), array('db.options' => $app['db.options']));
$app->register(new Silex\Provider\TranslationServiceProvider(), array(
    'translator.messages' => array(),
));

$app->register(new \Shorty\Provider\ShortyServiceProvider());
$app->register(new \Kurl\Silex\Provider\ErrorTemplateProvider());
//$app->register(new \Kurl\Silex\Auth\Provider\AuthServiceProvider());

if (true === $app['debug']) {
//    $app->register(
//        new Kurl\Silex\Provider\WebProfilerServiceProvider(),
//        array(
//            'profiler.cache_dir' => __DIR__ . '/../var/cache/profiler',
//        )
//    );
}

return $app;
