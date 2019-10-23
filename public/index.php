<?php

use Yaf\Application as YafApplication;

define('APPLICATION_PATH', realpath(dirname(__FILE__).'/../'));

// 金额以分为单位
define('PRICE_FORM', 100);
// 百分比以 10000 为单位
define('RATE_FORM', 10000);

include APPLICATION_PATH.'/vendor/autoload.php';

$application = new YafApplication( APPLICATION_PATH . "/conf/application.ini");

$application->bootstrap()->run();
