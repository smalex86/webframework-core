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
use smalex86\webframework\core\user\activeRecord\UserGroupAccess as UserGroupAccessRecord;

/**
 * UserGroupAccess Mapper
 *
 * @author Alexandr Smirnov <mail_er@mail.ru>
 */
class UserGroupAccess extends DataMapper {
  
  protected $tableName = 'core_user_group_access';
  
  protected function beforeInsert() {
    return null;
  }

  protected function getFields() {
    return null;
  }

  public function getById(int $id) {
    $query = sprintf('select * from %s where id = %u limit 1', $this->getTableName(), $id);
    $row = $this->database->selectSingleRow($query, __FILE__.':'.__LINE__);
    if ($row && is_array($row)) {
      return new UserGroupAccessRecord($row['id'], $row['user_group_id'], $row['object_name'], 
              $row['object_id'], $row['a_admin'], $row['a_read'], $row['a_write']);
    }
    return null;
  }
  
  /**
   * Получить набор правил доступа для группы и списка ее предков
   * @param int $groupIdChain Цепочка идентификаторов групп от корневой до целевой
   * @return array
   */
  public function getListForGroupIdChain(array $groupIdChain) 
  {
    $query = sprintf('SELECT * FROM `%s` WHERE user_group_id in (%s)', 
            $this->getTableName(), implode(',', $groupIdChain));
    $rows = $this->database->selectMultipleRows($query, __FILE__.':'.__LINE__);
    $groupAccessList = [];
    if (is_array($rows)) {
      $rules = $this->fillAccessRulesForGroup($rows, $groupIdChain);
      if (is_array($rules)) {
        foreach ($rules as $objectName=>$objectList) {
          foreach ($objectList as $objectId=>$object) {
            $groupAccessList[] = new UserGroupAccessRecord($object['id'], $object['user_group_id'], 
                    $objectName, $objectId, $object['a_admin'], $object['a_read'], 
                    $object['a_write']);
          }
        }
      }
    }
    return $groupAccessList;
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
  
  /**
   * Формирует массив правил доступа для группы и ее родительских групп
   * 
   * Алгоритм нахождения прав доступа для указанной группы доступа:
   * 1 выполняем цикл по групам доступа начиная с первого предка
   * 2 когда встречается новый объект, записываем его в массив:
   *  если ид = null, то добавить рез вида a[objName][0], 
   *  если указан ид, то рез вида a[objName][id]
   * 3 если встречается существующий объект в результирующем массиве, 
   * то перезаписываем права в результирующем массиве прав
   * 
   * @param array $allRules Массив записей для правил доступа из бд, формируется запросом в бд с 
   * фильтрацией по идентификаторам из цепочки идентификаторов групп
   * @param array $groupChain Цепочка идентификаторов групп от корневой до требуемой
   * @return array 
   */
  private function fillAccessRulesForGroup(array $allRules, array $groupChain): array
  {
    $res = []; // результирующий массив
    // цикл по предкам от корня
    foreach ($groupChain as $group) {
      // перебор правил доступа
      foreach ($allRules as $item) {
        // если правило доступа из текущего предка, то обрабатываем его
        if ($item['user_group_id'] == $group) {
          // проверка на новое имя объекта в рез
          if (!isset($res[$item['object_name']])) {
            // если новый, то проверяем на наличие ид и записываем в рез
            $index = $this->getAccessRuleObjectId($item['object_id']);
            $res[$item['object_name']][$index] = [
                  'id' => $item['id'],
                  'user_group_id' => $group,
                  'a_admin' => $item['a_admin'],
                  'a_read' => $item['a_read'],
                  'a_write' => $item['a_write']
              ];
          } else {
            // если такое имя уже встречалось
            // определяем ид
            $index = $this->getAccessRuleObjectId($item['object_id']);
            // проверяем есть ли элемент с таким именем и ид в рез
            if (!isset($res[$item['object_name']][$index])) {
              // добавляем новый элемент, если нет такого
              $res[$item['object_name']][$index] = [
                  'id' => $item['id'],
                  'user_group_id' => $group,
                  'a_admin' => $item['a_admin'],
                  'a_read' => $item['a_read'],
                  'a_write' => $item['a_write']
              ];
            } else {
              // если существует перезаписываем права
              $res[$item['object_name']][$index]['id'] = $item['id'];
              $res[$item['object_name']][$index]['user_group_id'] = $group;
              $res[$item['object_name']][$index]['a_admin'] = $item['a_admin'];
              $res[$item['object_name']][$index]['a_read'] = $item['a_read'];
              $res[$item['object_name']][$index]['a_write'] = $item['a_write'];
            }
          }
        }
      }
    }
    return $res;
  }
  
  /**
   * Определение индекса объекта для подстановки в результирующий набор правил для группы
   * @param int $inputIndex
   * @return int
   */
  private function getAccessRuleObjectId($inputIndex): int
  {
    if ($inputIndex == null) {
      $index = 0;
    } else {
      $index = $inputIndex;
    }
    return $index;
  }

}
