<?php

/*
 * This file is part of the smalex86 package.
 *
 * (c) Alexandr Smirnov <mail_er@mail.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace smalex86\webframework\core;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use smalex86\webframework\core\DatabasePDO;
use smalex86\webframework\core\Session;
use smalex86\webframework\core\ActiveRecord;

/**
 * Description of DataMapper
 *
 * @author Alexandr Smirnov
 */
abstract class DataMapper implements LoggerAwareInterface {
  
  use LoggerAwareTrait;
  
  /**
   * объект бд
   * @var DatabasePDO
   */
  protected $database;
  /**
   * объект для работы с сессией
   * @var Session
   */
  protected $session;
  /**
   * Название таблицы
   * @var string 
   */
  protected $tableName = '';
  
  public function __construct(DatabasePDO $database, Session $session) {
    $this->database = $database;
    $this->session = $session;
  }
  
  /**
   * метод возвращает название таблицы данных
   */
  protected function getTableName() 
  {
    return $this->tableName;
  }
  
  /**
   * возвращает список полей таблицы
   */
  abstract protected function getFields();
  
  /**
   * метод, выполняемый перед вставкой в бд
   */
  abstract protected function beforeInsert();
  
  /**
   * возвращает объект по идентификатору
   * @param int $id Идентификатор записи
   */
  abstract public function getById(int $id);
  
  /**
   * возвращает список объектов
   */
  abstract public function getList();
  
  /**
   * выполняет сохранение объекта в бд
   * @param ActiveRecord $record
   */
  abstract public function save(ActiveRecord $record);
  
  /**
   * выполняет обработку пост-данных
   */
  abstract public function processAction($postData = array());
  
}
