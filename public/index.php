<?php

include_once dirname(__DIR__) . '/vendor/autoload.php';
include_once dirname(__DIR__) . '/bootstrap.php';



$logger->debug(__FILE__.' : '.__LINE__.' -- POST = ' . var_export($_POST, true));
$logger->debug(__FILE__.' : '.__LINE__.' -- GET = ' . var_export($_GET, true));
$logger->debug(__FILE__.' : '.__LINE__.' -- file_get_contents = ' 
        . var_export(file_get_contents("php://input"), true));

$application->startActionManager(); // запуск обработки пост-данных
$application->startPageBuilder(); // запуск построения страницы

include('templates/main/index.php');
//include('templates/dashboard/index.php');