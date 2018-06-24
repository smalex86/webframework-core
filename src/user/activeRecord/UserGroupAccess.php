<?php

/*
 * This file is part of the smalex86 package.
 *
 * (c) Alexandr Smirnov <mail_er@mail.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace smalex86\webframework\core\user\activeRecord;

use smalex86\webframework\core\ActiveRecord;

/**
 * Description of UserGroupAccess
 *
 * @author Alexandr Smirnov <mail_er@mail.ru>
 */
class UserGroupAccess extends ActiveRecord {
  
  /**
   * Идентификатор
   * @var int
   */
  public $id;
  /**
   * Идентификатор группы пользователей
   * @var int
   */
  public $userGroupId;
  /**
   * Имя (алиас) объекта, на который задаются права
   * @var string
   */
  public $objectName;
  /**
   * Идентификатор объекта, на который задаются права.
   * Если указан, то права задаются на именно на данный объект, если не указан, то на все объекты 
   * указанного названия
   * @var int
   */
  public $objectId;
  /**
   * Если 1, то группа обладает администраторскими правами, иначе 0
   * @var int
   */
  public $aAdmin;
  /**
   * Если 1, то группе позволено читать данный объект или группу объектов, иначе 0
   * @var int
   */
  public $aRead;
  /**
   * Если 1, то группе позволено редактировать даный объект или группу объектов, иначе 0
   * @var int
   */
  public $aWrite;
  
  /**
   * Конструктор
   * @param int $id
   * @param int $userGroupId
   * @param string $objectName
   * @param int $objectId
   * @param int $aAdmin
   * @param int $aRead
   * @param int $aWrite
   */
  public function __construct($id, $userGroupId, $objectName, $objectId, $aAdmin, $aRead, $aWrite) {
    parent::__construct();
    $this->id = $id;
    $this->userGroupId = $userGroupId;
    $this->objectName = $objectName;
    $this->objectId = $objectId;
    $this->aAdmin = $aAdmin;
    $this->aRead = $aRead;
    $this->aWrite = $aWrite;
  }
  
}
