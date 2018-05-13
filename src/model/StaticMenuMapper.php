<?php

/*
 * This file is part of the smalex86 package.
 *
 * (c) Alexandr Smirnov <mail_er@mail.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace smalex86\webframework\core\Model;

use smalex86\webframework\core\DataMapper;
use smalex86\webframework\core\model\StaticMenu;

/**
 * Description of StaticMenuMapper
 *
 * @author Александр
 */
class StaticMenuMapper extends DataMapper {
  
   /**
   * метод возвращает название таблицы данных
   */
  protected function getTableName() {
    return 'menu';
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
  public function getById($id) {
    
  }
  
  public function getByAlias($alias) {
    $alias = $this->database->getSafetyString($alias);
    $query = sprintf('select * from %s where alias = "%s" limit 1', $this->getTableName(), $alias);
    $row = $this->database->selectSingleRow($query, __FILE__.':'.__LINE__);
    if ($row && isset($row['mid'])) {
      // загрузить пункты меню
      $query = sprintf('select * from menu_item where mid = %u', $row['mid']);
      $items = $this->database->selectMultipleRows($query, __FILE__.':'.__LINE__);
      if (is_array($items)) {
        return StaticMenu::newRecord($row['mid'], $row['name'], $row['alias'], $row['template'], 
                $row['type'], $items);
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
  public function save($obj) {
    
  }
  
  /**
   * выполняет обработку пост-данных
   */
  public function processAction($postData = array()) {
    
  }
  
}
