<?php

$app['twig.path']       = array(
    __DIR__ . '/../src/Shorty/Resources/views',
    __DIR__ . '/../src/Kurl/Silex/Auth/Resources/views',
);

$app['twig.options']    = array('cache' => __DIR__ . '/../var/cache/twig');
$app['asset_path']      = '/components';

$app['cdn_host'] = getenv('CDN_HOST') ?: 'krlcdn-14a4.kxcdn.com';

// TODO sort out this to use a CDN for production and local for local obviously.
// {{ app.asset_path }}/css/styles.css
$app['asset_path'] = $app->share(function ($app) {
    // implement whatever logic you need to determine the asset path

    return true === $app['debug'] ? '' : '//' . $app['cdn_host'];
});

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

$app['oauth.services.google.key'] = getenv('OAUTH_SERVICES_GOOGLE_KEY');
$app['oauth.services.google.secret'] = getenv('OAUTH_SERVICES_GOOGLE_SECRET');

$app->register(new Silex\Provider\DoctrineServiceProvider(), array('db.options' => $app['db.options']));