<?php

use Silex\Application;
use Silex\Provider\FormServiceProvider;
use Silex\Provider\MonologServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;
use Silex\Provider\ValidatorServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;

$app = new Application();
$app->register(new UrlGeneratorServiceProvider());
$app->register(new ValidatorServiceProvider());
$app->register(new ServiceControllerServiceProvider());
$app->register(new TwigServiceProvider());
$app['twig'] = $app->share($app->extend('twig', function ($twig, $app) {
    // add custom globals, filters, tags, ...

    return $twig;
}));
$app->register(new FormServiceProvider());
$app->register(new Silex\Provider\SessionServiceProvider());
$app->register(new MonologServiceProvider());
$app->register(new Silex\Provider\TranslationServiceProvider(), array(
    'translator.messages' => array(),
));

$app->register(new \Shorty\Provider\ShortyServiceProvider());
$app->register(new \Kurl\Silex\Provider\ErrorTemplateProvider());
$app->register(new \Kurl\Silex\Auth\Provider\AuthServiceProvider());

$app['assetic_librarian.assets'] = array(
    'bootstrap_css' => array(
        'assets' => array(__DIR__ . '/../vendor/components/bootstrap/css/bootstrap.min.css'),
        'filters' => array('?cssmin'),
        'options' => array('output' => 'css/bootstrap.css'),
    ),
    // TODO figure out why on earth assetic refuses to order dumped assets correctly.
    'bootstrap_js' => array(
        'assets' => array(
            //__DIR__ . '/../vendor/components/bootstrap/js/bootstrap.min.js',
            __DIR__ . '/../vendor/tagawa/bootstrap-without-jquery/bootstrap-without-jquery.min.js',
        ),
        'filters' => array('?jsmin'),
        'options' => array('output' => 'js/bootstrap.js'),
    ),
    'angular' => array(
        'assets' => array(
            __DIR__ . '/../vendor/components/angular.js/angular.min.js',
            __DIR__ . '/../vendor/components/angular-resource/angular-resource.min.js',
            __DIR__ . '/../vendor/angular-ui/bootstrap/ui-bootstrap.min.js',
        ),
        'filters' => array('?jsmin'),
        'options' => array('output' => 'js/angular.js'),
    ),
    'krl_js' => array(
        'assets' => array(
            __DIR__ . '/Shorty/Resources/public/js/services.js',
            __DIR__ . '/Shorty/Resources/public/js/controllers.js',
            __DIR__ . '/Shorty/Resources/public/js/filters.js'
        ),
        'filters' => array(),
        'options' => array('output' => 'js/krl.js'),
    ),
    'krl_css' => array(
        'assets' => array(
            __DIR__ . '/Shorty/Resources/public/css/*.css'
        ),
        'filters' => array('?cssmin'),
        'options' => array('output' => 'css/krl.css'),
    )
);

$app->register(new SilexAssetic\AsseticServiceProvider());

$app['assetic.filter_manager'] = $app->share(
    $app->extend('assetic.filter_manager', function($fm, $app) {

        $fm->set('cssmin', new Assetic\Filter\CssMinFilter());
        $fm->set('jsmin', new Assetic\Filter\JSMinFilter());

        return $fm;
    })
);

$app['assetic.path_to_web'] = __DIR__ . '/../web';
$app['assetic.options'] = array(
    'formulae_cache_dir' => __DIR__ . '/../var/cache/assetic-formulae',
    'cache_dir' => __DIR__ . '/../var/cache/assetic',
    'debug'              => $app['debug'],
    'auto_dump_assets' => true
);

$app->extend('twig', function (\Twig_Environment $twig, Application $app) {
    $twig->addExtension(new Assetic\Extension\Twig\AsseticExtension($app['assetic.factory']));

    return $twig;
});

$app['assetic.factory'] = $app->share(
    $app->extend('assetic.factory', function (\Assetic\Factory\AssetFactory $factory, $app) {
        foreach ($app['assetic_librarian.assets'] as $name => $set) {
            $factory->getAssetManager()->set(
                $name,
                new \Assetic\Asset\AssetCache(
                    $factory->createAsset($set['assets'], $set['filters'], $set['options']),
                    new Assetic\Cache\FilesystemCache($app['assetic.options']['cache_dir'])
                )
            );
        }

        return $factory;
    })
);

return $app;
