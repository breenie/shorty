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

//$app['db.options']      = array(
//    'driver'   => 'pdo_mysql',
//    'dbname'   => ltrim($dbopts['path'], '/'),
//    'host'     => $dbopts['host'],
//    'port'     => $dbopts['port'],
//    'user'     => $dbopts['user'],
//    'password' => $dbopts['pass']
//);

$app['db.options'] = array();

$app->register(new Silex\Provider\DoctrineServiceProvider(), array('db.options' => $app['db.options']));