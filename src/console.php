<?php

use Symfony\Component\Console\Application;

$console = new Application('Shorty console', '0.0.0');

$app['migrations.options'] = array();
$app['migrations.namespace']  = 'Shorty\Migrations';
$app['migrations.directory']  = realpath(__DIR__ . '/../src/Shorty/Migrations');
$app['migrations.name']       = 'Shorty';
$app['migrations.table_name'] = 'shorty_migration_versions';

$app->register(
    new \Kurl\Silex\Provider\DoctrineMigrationsProvider($console),
    array(
        'migrations.namespace'  => $app['migrations.namespace'],
        'migrations.directory'  => $app['migrations.directory'],
        'migrations.name'       => $app['migrations.name'],
        'migrations.table_name' => $app['migrations.table_name'],
    )
);

return $console;
