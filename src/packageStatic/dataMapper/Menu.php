<?php

/*
 * This file is part of the smalex86 package.
 *
 * (c) Alexandr Smirnov <mail_er@mail.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace smalex86\webframework\core\packageStatic\dataMapper;

use smalex86\webframework\core\{DataMapper, ActiveRecord};
use smalex86\webframework\core\packageStatic\activeRecord\Menu as MenuRecord;

/**
 * Description of Menu
 *
 * @author Alexandr Smirnov <mail_er@mail.ru>
 */
class Menu extends DataMapper {
  
   /**
   * метод возвращает название таблицы данных
   */
  protected function getTableName() {
    return 'core_menu';
  }
  
  /**
   * возвращает список полей таблицы
   */
  protected function getFields() {
    return array();
  }
  
  /**
   * метод, выполняемый перед вставкой в бд
   */
  protected function beforeInsert() {
    
  }
  
  /**
   * возвращает объект по идентификатору
   */
  public function getById(int $id) {
    
  }
  
  public function getByAlias($alias) {
    $alias = $this->database->getSafetyString($alias);
    $query = sprintf('select * from %s where name = "%s" limit 1', $this->getTableName(), $alias);
    $row = $this->database->selectSingleRow($query, __FILE__.':'.__LINE__);
    if ($row && isset($row['id'])) {
      // загрузить пункты меню
      $query = sprintf('select * from core_menu_item where menu_id = %u', $row['id']);
      $items = $this->database->selectMultipleRows($query, __FILE__.':'.__LINE__);
      if (is_array($items)) {
        return MenuRecord::newRecord($row['id'], $row['name'], $row['template'], $row['type'], 
                $items);
      }
      return null;
    }
    return null;
  }
  
  /**
   * возвращает список объектов
   */
  public function getList() {
    
  }
  
  /**
   * выполняет сохранение объекта в бд
   */
  public function save(ActiveRecord $record) {
    
  }
  
  /**
   * выполняет обработку пост-данных
   */
  public function processAction($postData = array()) {
    
  }
  
}