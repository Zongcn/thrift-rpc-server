<?php
error_reporting(E_ALL|E_STRICT);
date_default_timezone_set('Etc/GMT-8');

define('PUBLIC_PATH', dirname(__DIR__));
define('APPLICATION_PATH', PUBLIC_PATH . "/Application");
define('LIBRARY_PATH', PUBLIC_PATH . "/Library");
require_once LIBRARY_PATH . "/Thrift/ClassLoader/ThriftClassLoader.php";
use Thrift\ClassLoader\ThriftClassLoader;

$loader = new ThriftClassLoader();
$loader->registerNamespace('Thrift', LIBRARY_PATH);
$loader->registerNamespace('Swoole', LIBRARY_PATH);
$loader->registerNamespace('Services', APPLICATION_PATH);
$loader->registerDefinition('Services',  APPLICATION_PATH);
$loader->register();

$application = \Swoole\Application::getInstance();
$application->run();