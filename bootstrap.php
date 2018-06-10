<?php

// подключение автозагрузчика классов 
include_once __DIR__ . '/vendor/autoload.php';

/* Настройки php */
ini_set("session.use_trans_sid", true); // поддержка использования SID (идентификатора сессии)

if (!defined("PATH_SEPARATOR")) {
  define("PATH_SEPARATOR", getenv("COMSPEC")? ";" : ":");
}
if (!defined("DIRECTORY_SEPARATOR")) {
  define("DIRECTORY_SEPARATOR ", "/");
}

// определение пути для файлов конфигурации
$configPath = __DIR__ . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR; 
// загрузка файлов конфигурации
$config = [];
$files = glob($configPath . '*.php');
foreach ($files as $file) {
  $config = array_merge($config, include $file);
}

// конфигурация
$config = new ArrayObject($config, ArrayObject::ARRAY_AS_PROPS);
// логгер
$logger = new smalex86\logger\Logger();
$logger->routeList->attach(new smalex86\logger\route\FileRoute([
    'isEnabled' => true,
    'maxLevel' => $config['logger']['status'],
    'logFile' => $config['logger']['logfile'],
    'folder' => $config['logger']['logFolder']
]));
// база данных
$database = new smalex86\webframework\core\Database(
        $logger, 
        $config['db']['host'], 
        $config['db']['username'], 
        $config['db']['password'], 
        $config['db']['name']);
// приложение
$application = new smalex86\webframework\core\Server($config, $logger, $database);