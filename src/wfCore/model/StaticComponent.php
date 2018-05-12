<?php

/*
 * This file is part of the smalex86 package.
 *
 * (c) Alexandr Smirnov <mail_er@mail.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace smalex86\wfCore\Model;

use smalex86\wfCore\ActiveRecord;

/**
 * Description of StaticComponent
 *
 * @author Alexandr Smirnov
 */
class StaticComponent extends ActiveRecord {
  
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
   * @return \smalex86\wfCore\model\StaticComponent
   */
  static public function newRecord($comid, $name, $text, $filename) {
    $record = new StaticComponent;
    $record->comid = $comid;
    $record->name = $name;
    $record->text = $text;
    $record->filename = $filename;
    return $record;
  }
  
}
