<?php

use Kurl\Silex\Provider\WebProfilerServiceProvider;

// include the prod configuration
require __DIR__ . '/prod.php';

// enable the debug mode
$app['debug'] = true;
$app['monolog.logfile'] = __DIR__ . '/../var/logs/silex_dev.log';

$app['db.options']      = array(
    'driver'   => 'pdo_mysql',
    'dbname'   => 'shorty',
    'host'     => '10.0.0.2',
    'port'     => '3306',
    'user'     => 'root',
    'password' => 'root'
);

$app['oauth.services.google.key'] = '1043378317437-28gh0nh49o9g3v8sppqvr7oc4dtk4ohq.apps.googleusercontent.com';
$app['oauth.services.google.secret'] = 'VnzIQloHrFdkb7-9N3466nM3';

$app->register(
    new WebProfilerServiceProvider(),
    array(
        'profiler.cache_dir' => __DIR__ . '/../var/cache/profiler',
    )
);
