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
use smalex86\webframework\core\packageStatic\activeRecord\Page as PageRecord;

/**
 * Description of Page
 *
 * @author Alexandr Smirnov <mail_er@mail.ru>
 */
class Page extends DataMapper {
  
   /**
   * метод возвращает название таблицы данных
   */
  protected function getTableName() {
    return 'core_page';
  }
  
  /**
   * возвращает список полей таблицы
   */
  protected function getFields() {
    return array('id', 'page_section_id', 'alias', 'link', 'title', 'name', 'teaser', 
        'text', 'date_create', 'date_public', 'date_update', 'published');
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
    $query = sprintf('select * from %s where alias = "%s" limit 1', $this->getTableName(), $alias);
    $row = $this->database->selectSingleRow($query, __FILE__.':'.__LINE__);
    if ($row) {
      return PageRecord::newRecord($row['id'], $row['page_section_id'], $row['alias'], $row['link'], 
              $row['title'], $row['name'], $row['teaser'], $row['text'], $row['date_create'],
              $row['date_public'], $row['date_update'], $row['published']);
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
