<?php
/**
 * The production web bootstrap.
 */
namespace {
    date_default_timezone_set('Europe/London');
    ini_set('date.timezone', 'Europe/London');

    //ini_set('display_errors', 0);

    if ('cli-server' === php_sapi_name() &&
        true === is_file(__DIR__ . preg_replace('#(\?.*)$#', '', $_SERVER['REQUEST_URI']))
    ) {
        return false;
    }

    require_once __DIR__ . '/../vendor/autoload.php';

    $app = require_once __DIR__ . '/../src/app.php';

    $app->run();
}