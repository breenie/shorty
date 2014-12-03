<?php

use Silex\Provider\WebProfilerServiceProvider;

// include the prod configuration
require __DIR__ . '/prod.php';

// enable the debug mode
$app['debug'] = true;
$app['monolog.logfile'] = __DIR__ . '/../var/logs/silex_dev.log';
$app['db.options']      = array(
    'driver'   => 'pdo_pgsql',
    'dbname'   => 'shorty',
    'host'     => '127.0.0.1',
    'port'     => '5432',
    'user'     => 'chris',
    //'password' => ''
);

$app->register(
    new WebProfilerServiceProvider(),
    array(
        'profiler.cache_dir' => __DIR__ . '/../var/cache/profiler',
    )
);
$app->register(new Silex\Provider\DoctrineServiceProvider(), $app['db.options']);
