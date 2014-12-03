<?php
/**
 * The production web bootstrap.
 */
namespace {
    ini_set('display_errors', 0);

    if ('cli-server' === php_sapi_name() &&
        true === is_file(__DIR__ . preg_replace('#(\?.*)$#', '', $_SERVER['REQUEST_URI']))
    ) {
        return false;
    }

    require_once __DIR__ . '/../vendor/autoload.php';

    $app = require_once __DIR__ . '/../src/app.php';
    require_once __DIR__ . '/../config/prod.php';
    require_once __DIR__ . '/../src/controllers.php';
    $app->run();
}