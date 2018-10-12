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
    $query = sprintf('select * from %s where name = :name limit 1', 
            $this->getTableName());
    $params = ['name' => $alias];
    try {
      $rowMenu = $this->database->selectSingleRow($query, $params);
      if ($rowMenu && isset($rowMenu['id'])) {
        // загрузить пункты меню
        $query = 'select * from core_menu_item where menu_id = :menu_id';
        $params = ['menu_id' => $rowMenu['id']];
        $rowMenuItems = $this->database->selectMutlipleRows($query, $params);
        if (is_array($rowMenuItems)) {
          return MenuRecord::newRecord($rowMenu['id'], $rowMenu['name'], 
                  $rowMenu['template'], $rowMenu['type'], $rowMenuItems);
        }
        return null;
      }
    } catch (DatabaseException $e) {
      
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
