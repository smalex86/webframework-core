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
 * Description of Page
 *
 * @author Alexandr Smirnov
 */
class Page extends ActiveRecord {
  
  public $id;
  public $pageSectionId;
  public $alias;
  public $link;
  public $title;
  public $name;
  public $teaser;
  public $text;
  public $dateCreate;
  public $datePublic;
  public $dateUpdate;
  public $published;
  
  /**
   * Данный статический метод создает экземпляр данного класса с указанными параметрами
   * @param type $id
   * @param type $pageSectionId
   * @param type $alias
   * @param type $link
   * @param type $title
   * @param type $name
   * @param type $teaser
   * @param type $text
   * @param type $dateCreate
   * @param type $datePublic
   * @param type $dateUpdate
   * @param type $published
   * @return \smalex86\webframework\core\packageStatic\activeRecord\Page
   */
  static public function newRecord($id, $pageSectionId, $alias, $link, $title, $name, 
          $teaser, $text, $dateCreate, $datePublic, $dateUpdate, $published) {
    $record = new Page;
    $record->pid = $id;
    $record->pageSectionId = $pageSectionId;
    $record->alias = $alias;
    $record->link = $link;
    $record->title = $title;
    $record->name = $name;
    $record->teaser = $teaser;
    $record->text = $text;
    $record->dateCreate = $dateCreate;
    $record->datePublic = $datePublic;
    $record->dateUpdate = $dateUpdate;
    $record->published = $published;
    return $record;
  }
  
  
  
}
