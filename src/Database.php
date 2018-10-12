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

/**
 * Database
 *
 * @author Alexandr Smirnov
 * 
 * @deprecated since 2018.10.12
 */
class Database {
  
  public $errno = 0; // код ошибки
  public $errstr = ''; // текст ошибки
  public $mysqli = null; // ссылка на объект класса mysqli	
  private $logger = null;

  /**
   * конструктор класса
   * реализует внутри себя подключение к бд и проверку на ошибки
   */
  function __construct(LoggerInterface $logger, $host, $username, $password, $name) {
    $this->logger = $logger;
    $this->mysqli = new \mysqli($host, $username, $password, $name);
    if ($this->mysqli->connect_error) {
      $this->errno = $this->mysqli->connect_errno;
      $this->errstr = $this->mysqli->connect_error;
      $msg = 'Connection error (' . $this->errno . '): ' . $this->errstr;
      $this->logger->error($msg);
      die($msg);
    }  
    else if (!$this->mysqli->set_charset('utf8')) {
      $this->logger->warning("Ошибка при загрузке набора символов utf8: " . $this->mysqli->error);
    }
  }
  
  public function getLastError() {
    return sprintf('(%u) %s', $this->mysqli->errno, $this->mysqli->error);
  }

  /**
   * Метод для получения объекта mysqli с установленным соединением
   * @return mysqli|boolean
   */
  function getMysql() {
    if (($this->mysqli) && (!$this->errno)) {
      return $this->mysqli;
    }
    return false;
  }

  /**
   * заглушка для функции fetch_all
   * @param unknown $result
   * @return NULL|unknown
   */
  function fetchAll($result) {
    $data = null;
    while($row = mysqli_fetch_assoc($result)) {
      $data[] = $row;
    }
    return $data;
  }
  
  /**
   * Выполняет обработку строки для обезопасивания перед вставкой в sql запрос
   * @param string $str
   * @return string
   */
  public function getSafetyString($str) {
    return $this->mysqli->real_escape_string($str);
  }
  
  /**
   * Выполняет обработку массива строк для обезопасивания перед вставкой в sql запрос
   * @param array $data
   * @return array
   */
  public function getSafetyStringList(array $data): array
  {
    foreach ($data as $key=>$value) {
      if (is_string($value)) {
        $data[$key] = $this->getSafetyString($value);
      }
    }
    return $data;
  }
  
  /**
   * Метод выполняет обращение к базе данных, результатом которого является единственная строка
   * @param string $query запрос
   * @param string $place место, из которого он вызывается
   * @return array
   */
  public function selectSingleRow($query, $place) {
    return $this->queryProcess('singleSelect', $query, $place);
  }
  
  /**
   * Метод выполняет обращение к базе данных, результатом которого является массив строк
   * @param string $query запрос
   * @param string $place место, из которого он вызывается
   * @return array of array
   */
  public function selectMultipleRows($query, $place) {
    return $this->queryProcess('multipleSelect', $query, $place);
  }
  
  /**
   * Метод для вставки одиночной записи в бд
   * @param string $query
   * @param string $place
   * @return int|string
   */
  public function insertSingle($query, $place) {
    $insertId = $this->queryProcess('singleInsert', $query, $place);
    if ($insertId) {
      $msg = $place.': insert_id = '.$insertId;
      $this->logger->debug($msg);
    }
    return $insertId;
  }
  
  /**
   * Метод для обновления одиночной записи в бд
   * @param string $query
   * @param string $place
   * @return bool
   */
  public function updateSingle($query, $place) {
    $result = $this->queryProcess('singleUpdate', $query, $place);
    if ($result === true) {
      $msg = $place.': update = true';
      $this->logger->debug($msg);
    }
    return $result;
  }
  
  /**
   * Метод обрабатывает запрос к базе данных с указанием в каком виде данные вернуть
   * @param string $queryType тип возвращаемых данных 
   * @param string $query запрос
   * @param string $place место, из которого вызывается запрос
   * @return array|array of array|null
   */
  private function queryProcess($queryType, $query, $place) {
    $msg = $place.': query = '.$query;
    $this->logger->debug($msg);
    if (!$query) {
      return null;
    }
    if ($result = $this->mysqli->query($query)) {
      return $this->queryResultProcess($queryType, $result);
    } else {
      $msg = $place.': Ошибка при выполнении запроса ('.$this->mysqli->errno.'): '
              .$this->mysqli->error;
      $this->logger->error($msg);
      return null;
    }
  }
  
  /**
   * Метод обрабатывает результат работы запроса чтобы получить данные в виде массива
   * @param string $queryType
   * @param mysqlResult $result
   * @return array|array of array
   */
  private function queryResultProcess($queryType, $result) {
    switch ($queryType) {
      case 'singleSelect':
        $row = $result->fetch_assoc();
        $result->close();
        return $row;
      case 'multipleSelect':
        $rows = $this->fetchAll($result);
        $result->close();
        return $rows;
      case 'singleInsert':
        return $this->mysqli->insert_id; // при добавлении одной записи возвращаем ее новый ид
      case 'singleUpdate':
        return $result; // при обновлении возвращаем просто результат
      default:
        break;
    }
  }
  
}
