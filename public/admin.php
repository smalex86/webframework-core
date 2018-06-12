<?php

include_once dirname(__DIR__) . '/bootstrap.php';

$logger->debug('POST = ' . var_export($_POST, true));
$logger->debug('GET = ' . var_export($_GET, true));
$logger->debug('file_get_contents = ' . var_export(file_get_contents("php://input"), true));

$application->startActionManager(); // запуск обработки пост-данных
$application->startPageBuilder(); // запуск построения страницы

include('templates/dashboard/index.php');