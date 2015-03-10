<?php

use Silex\Provider\WebProfilerServiceProvider;

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

$app->register(
    new WebProfilerServiceProvider(),
    array(
        'profiler.cache_dir' => __DIR__ . '/../var/cache/profiler',
    )
);
