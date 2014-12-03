<?php

/**
 * The development web bootstrap.
 */
namespace {
    use Symfony\Component\Debug\Debug;

    ini_set('display_errors', 1);

    // This check prevents access to debug front controllers that are deployed by accident to production servers.
    // Feel free to remove this, extend it, or make something more sophisticated.

    if (isset($_SERVER['HTTP_CLIENT_IP'])
        || isset($_SERVER['HTTP_X_FORWARDED_FOR'])
        || !in_array(@$_SERVER['REMOTE_ADDR'], array('127.0.0.1', 'fe80::1', '::1'))
    ) {
        header('HTTP/1.0 403 Forbidden');
        exit('You are not allowed to access this file. Check ' . basename(__FILE__) . ' for more information.');
    }

    if ('cli-server' === php_sapi_name() &&
        true === is_file(__DIR__ . preg_replace('#(\?.*)$#', '', $_SERVER['REQUEST_URI']))
    ) {
        return false;
    }

    require_once __DIR__ . '/../vendor/autoload.php';

    Debug::enable();

    $app = require_once __DIR__ . '/../src/app.php';
    require_once __DIR__ . '/../config/dev.php';
    require_once __DIR__ . '/../src/controllers.php';
    $app->run();
}