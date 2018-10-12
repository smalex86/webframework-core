<?php

/*
 * This file is part of the smalex86\webframework\core package.
 *
 * (c) Alexandr Smirnov <mail_er@mail.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace smalex86\webframework\core;

use Psr\Log\LoggerInterface;
use smalex86\webframework\core\exception\DatabaseException;

/**
 * Класс для работы с базами данных на основе библиотеки PDO
 *
 * @author Alexandr Smirnov
 */
class DatabasePDO {

    use \Psr\Log\LoggerAwareTrait;

    /**
     * Строка подлючения
     * @var string 
     */
    protected $dsn = "";

    /**
     * Настройки подключения
     * @var array 
     */
    protected $options = [
        \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
        \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
        \PDO::ATTR_EMULATE_PREPARES => false,
    ];

    /**
     * Объект PDO для работы с БД
     * @var \PDO
     */
    protected $pdo = null;

    /**
     * Информация о последней ошибке
     * @var array
     */
    protected $lastErrorInfo = [];

    /**
     * конструктор класса
     * реализует внутри себя подключение к бд и проверку на ошибки
     * @param LoggerInterface $logger
     * @param string $type mysql, pgsql
     * @param string $host
     * @param int $port 
     * @param string $username
     * @param string $password
     * @param string $name database name
     * @throws DatabaseException
     */
    function __construct(LoggerInterface $logger, $type, $host, $port, 
            $username, $password, $name) {
        $this->logger = $logger;
        $this->dsn = $this->getDsn($type, $host, $port, $name);
        try {
            $this->pdo = new \PDO($this->dsn, $username, $password, $this->options);
        } catch (\PDOException $e) {
            $this->logger->error($e->getMessage());
            throw new DatabaseException($e->getMessage(), 0, $e);
        }
    }

    /**
     * Информация о последней ошибке PDO
     * затирает состояние последней ошибки
     * @return string
     */
    public function getLastError() {
        $result = var_export($this->lastErrorInfo, true);
        $this->lastErrorInfo = [];
        return $result;
    }

    /**
     * Выполняет подготовку запроса с переменными
     * Далее следует выполнить метод execute() у полученного объекта
     * @param string $query sql-запрос
     * @return \PDOStatement
     * @throws DatabaseException
     */
    public function prepare($query) {
        try {
            return $this->pdo->prepare($query);
        } catch (\PDOException $pe) {
            $this->logger->error('PDOException: ' . $pe->getMessage());
            $this->lastErrorInfo = $this->pdo->errorInfo();
            throw new DatabaseException($pe->getMessage(), 0, $pe);
        }
    }

    /**
     * Выполняет запрос без переменных
     * Следует использовать данный метод когда в запросе нет внешних данных
     * @param string $query
     * @return \PDOStatement
     * @throws DatabaseException
     */
    public function query($query) {
        try {
            return $this->pdo->query($query);
        } catch (\PDOException $pe) {
            $this->logger->error('PDOException: ' . $pe->getMessage());
            $this->lastErrorInfo = $this->pdo->errorInfo();
            throw new DatabaseException($pe->getMessage(), 0, $pe);
        }
    }

    /**
     * Сформировать dsn для подключения к БД
     * @param string $type pgsql || mysql
     * @param string $host
     * @param int $port
     * @param string $database
     * @return string
     */
    private function getDsn($type, $host, $port, $database) {
        return sprintf("%s:host=%s;port=%s;dbname=%s", $type, $host, $port, $database);
    }

}
