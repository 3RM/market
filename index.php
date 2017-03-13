<?php

//FRONT CONTROLLER
//1. Общие настройки
ini_set('display_errors', 0);
error_reporting(E_ALL);

//session_start();

//2. Подключение файлов к системе
define('ROOT', dirname(__FILE__));
require_once ROOT . '/components/Autoload.php';
require_once ROOT. '/components/Router.php';
//require ROOT . '/vendor/autoload.php';
//require __DIR__ . '/../vendor/autoload.php';

//3. Установка соединения с БД
//4. Вызов Router
$router = new Router();
$router->run();
