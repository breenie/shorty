<?php
/**
 * - bootstrap.php
 *
 * @author chris
 * @created 10/03/15 17:40
 */

$app = require __DIR__ . '/../src/app.php';

$app['debug'] = true;
$app['session.test'] = true;

$app['db'] = $db = $this->getMockBuilder('Doctrine\DBAL\Connection')->disableOriginalConstructor()->getMock();
//$app['db.options'] = array(
//    'driver' => 'pdo_sqlite',
//    'path'   => ':memory:',
//);

return $app;
