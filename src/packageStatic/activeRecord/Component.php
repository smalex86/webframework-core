<?php

/*
 * This file is part of the smalex86 package.
 *
 * (c) Alexandr Smirnov <mail_er@mail.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace smalex86\webframework\core\packageStatic\activeRecord;

use smalex86\webframework\core\ActiveRecord;

/**
 * Description of Component
 *
 * @author Alexandr Smirnov
 */
class Component extends ActiveRecord {
  
  public $comid;
  public $name;
  public $text;
  public $filename;
  
  /**
   * Данный статический метод создает экземпляр данного класса с указанными параметрами
   * @param int $comid
   * @param string $name
   * @param string $text
   * @param string $filename
   * @return \smalex86\webframework\core\packageStatic\activeRecord\Component
   */
  static public function newRecord($comid, $name, $text, $filename) {
    $record = new Component;
    $record->comid = $comid;
    $record->name = $name;
    $record->text = $text;
    $record->filename = $filename;
    return $record;
  }
  
}
