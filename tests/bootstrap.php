<?php
/**
 * - bootstrap.php
 *
 * @author chris
 * @created 10/03/15 17:40
 */

$app = require __DIR__ . '/../src/app.php';
require_once __DIR__ . '/../config/dev.php';

$app['debug'] = true;
$app['session.test'] = true;
$app['monolog.logfile'] = __DIR__ . '/../var/logs/silex_test.log';

$app['db'] = $db = $this->getMockBuilder('Doctrine\DBAL\Connection')->disableOriginalConstructor()->getMock();
//$app['db.options'] = array(
//    'driver' => 'pdo_sqlite',
//    'path'   => ':memory:',
//);

return $app;
