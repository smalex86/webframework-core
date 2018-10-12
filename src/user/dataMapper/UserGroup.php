<?php

/*
 * This file is part of the smalex86 package.
 *
 * (c) Alexandr Smirnov <mail_er@mail.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace smalex86\webframework\core\user\dataMapper;

use smalex86\webframework\core\{ActiveRecord, DataMapper};
use smalex86\webframework\core\user\activeRecord\UserGroup as UserGroupRecord;

/**
 * UserGroup Mapper
 *
 * @author Alexandr Smirnov <mail_er@mail.ru>
 */
class UserGroup extends DataMapper {
  
  protected $tableName = 'core_user_group';
  
  protected function beforeInsert() {
    return null;
  }

  protected function getFields() {
    return null;
  }

  public function getById(int $id) {
    $query = sprintf('select * from %s where id = :id limit 1', 
            $this->getTableName());
    $params = ['id' => $id];
    $row = $this->database->selectSingleRow($query, $params);
    if ($row && is_array($row)) {
      return new UserGroupRecord($row['id'], $row['parent_id'], $row['name'], 
              $row['description']);
    }
    return null;
  }
  
  /**
   * Возвращает список родительских категорий пользователей
   * @param int $id
   * @return int[]
   */
  public function getParentListById(int $id) 
  {
    $query = sprintf('SELECT l2.id as id2, l3.id as id3, l4.id as id4, l5.id as id5 '
            . 'FROM `%1$s` l1 '
            . 'LEFT JOIN `%1$s` AS l2 ON l2.id = l1.parent_id '
            . 'LEFT JOIN `%1$s` AS l3 ON l3.id = l2.parent_id '
            . 'LEFT JOIN `%1$s` AS l4 ON l4.id = l3.parent_id '
            . 'LEFT JOIN `%1$s` AS l5 ON l5.id = l4.parent_id '
            . 'WHERE l1.id = :id limit 1', $this->tableName);
    $params = ['id' => $id];
    $row = $this->database->selectSingleRow($query, $params);
    if (is_array($row)) {
      $result = [];
      for ($i = 5; $i >= 2; $i--) {
        if ($row['id'.$i] != null) {
          $result[] = $row['id'.$i];
        }
      }
      return $result;
    }
    return null;
  }

  public function getList() {
    return null;
  }

  public function processAction($postData = array()) {
    return null;
  }

  public function save(ActiveRecord $record) {
    return null;
  }

}
