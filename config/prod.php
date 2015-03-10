<?php

$app['twig.path']       = array(__DIR__ . '/../templates');
$app['twig.options']    = array('cache' => __DIR__ . '/../var/cache/twig');
$app['asset_path']      = '/components';
$app['monolog.logfile'] = __DIR__ . '/../var/logs/silex_prod.log';

$app['migrations.options'] = array();
$app['migrations.namespace']  = 'Shorty\Migrations';
$app['migrations.directory']  = realpath(__DIR__ . '/../src/Shorty/Migrations');
$app['migrations.name']       = 'Shorty';
$app['migrations.table_name'] = 'shorty_migration_versions';

$app['db.options']      = array(
    'driver'   => 'pdo_mysql',
    'dbname'   => getenv('DB_NAME'),
    'host'     => getenv('DB_HOST'),
    'port'     => getenv('DB_PORT'),
    'user'     => getenv('DB_USER'),
    'password' => getenv('DB_PASS'),
);

$app->register(new Silex\Provider\DoctrineServiceProvider(), array('db.options' => $app['db.options']));