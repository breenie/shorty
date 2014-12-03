<?php

$dbopts = parse_url(getenv('DATABASE_URL'));
// configure your app for the production environment

$app['twig.path']       = array(__DIR__ . '/../templates');
$app['twig.options']    = array('cache' => __DIR__ . '/../var/cache/twig');
$app['asset_path']      = '/components';
$app['monolog.logfile'] = __DIR__ . '/../var/logs/silex_prod.log';
//$app['db.options']      = array(
//    'driver'   => 'pdo_pgsql',
//    'dbname'   => ltrim($dbopts['path'], '/'),
//    'host'     => $dbopts['host'],
//    'port'     => $dbopts['port'],
//    'user'     => $dbopts['user'],
//    'password' => $dbopts['pass']
//);
