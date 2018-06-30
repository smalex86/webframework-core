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
$configObj = new ArrayObject($config, ArrayObject::ARRAY_AS_PROPS);
// логгер
$logger = new smalex86\logger\Logger();
$logger->routeList->attach(new smalex86\logger\route\FileRoute([
    'isEnabled' => true,
    'maxLevel' => $configObj->logger['status'],
    'logFile' => $configObj->logger['logfile'],
    'folder' => $configObj->logger['logFolder']
]));
// инициализация переменных для замены в строках (ссылках)
\smalex86\webframework\core\FunctionList::variableArrayInit($config['linkVariable'], $logger);
// база данных
$database = new smalex86\webframework\core\Database(
        $logger, 
        $configObj->db['host'], 
        $configObj->db['username'], 
        $configObj->db['password'], 
        $configObj->db['name']);
// приложение
$application = new smalex86\webframework\core\Server($configObj, $database);
$application->setLogger($logger);

$userMapper = new smalex86\webframework\core\user\dataMapper\User($application->getDatabase(), 
        $application->getSession());
$userMapper->setLogger($logger);
$user = $userMapper->getActiveUser();
if (!$user) {
  $user = new \smalex86\webframework\core\user\activeRecord\User(null, '', '', null, '', '', '', 
          '', '', '', '', '', '');
}
$user->setApplication($application);