<?php

// Общие настройки
/*ini_set('display_errors',1);
error_reporting(E_ALL);*/
error_reporting(E_ERROR | E_PARSE | E_CORE_ERROR | E_COMPILE_ERROR | E_USER_ERROR);

// Подключение файлов системы 1
define('ROOT', dirname(__FILE__));
require_once(ROOT.'/components/Autoload.php');

// Подключение констант
require_once(ROOT.'/config/constants.php');

// Вызов Router
$router = new App();
$router->run();