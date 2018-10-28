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
use smalex86\webframework\core\Server;
use smalex86\webframework\core\user\activeRecord\UserGroupAccess;
use smalex86\webframework\core\user\dataMapper\UserGroupAccess as UserGroupAccessMapper;
use smalex86\webframework\core\user\dataMapper\UserGroup as UserGroupMapper;

/**
 * UserGroup
 *
 * @author Alexandr Smirnov <mail_er@mail.ru>
 */
class UserGroup extends ActiveRecord {
  
  /** 
   * Идентификатор
   * @var int
   */
  public $id;
  /**
   * Идентификатор родительской группы
   * @var int 
   */
  public $parentId;
  /**
   * Название группы
   * @var string
   */
  public $name;
  /**
   * Описание
   * @var string
   */
  public $description;
  
  /**
   * Объект приложения
   * @var Server
   */
  protected $application;
  /**
   * Список объектов правил доступа для группы
   * @var UserGroupAccess[]
   */
  protected $accessList;
  
  public function __construct($id, $parentId, $name, $description) {
    parent::__construct();
    $this->id = $id;
    $this->parentId = $parentId;
    $this->name = $name;
    $this->description = $description;
  }
  
  /**
   * Задать объект приложения
   * @param Server $application
   */
  public function setApplication(Server $application)
  {
    $this->application = $application;
  }
  
  /**
   * Получить список правил доступа для группы
   * @return UserGroupAccess[]
   */
  public function getAccessList(): array
  {
    if (!$this->id) {
      return [];
    }
    if (!$this->accessList) {
      if (!$this->application) {
        return null;
      } else {
        $userGroupMapper = new UserGroupMapper($this->application->getDatabase(), 
                $this->application->getSession());
        $groupChain = array_merge($userGroupMapper->getParentListById($this->id), [$this->id]);
        $userGroupAccessMapper = new UserGroupAccessMapper($this->application->getDatabase(), 
                $this->application->getSession());
        $this->accessList = $userGroupAccessMapper->getListForGroupIdChain($groupChain);
      }
    }
    return $this->accessList;
  }
  
  /**
   * Проверка на наличие административных прав доступа
   * @return bool
   */
  public function isAdmin(): bool
  {
    if (!$this->id) {
      return false;
    }
    // проверка инициализации списка правил доступа
    if (!$this->accessList) {
      // если при инициализации был получен null, то вернуть false
      if ($this->getAccessList() === null) {
        return false;
      }
    }
    // цикл по списку правил доступа
    foreach ($this->accessList as $accessItem) {
      // если в каком-либо из правил есть признак административного доступа, то вернуть true
      if ($accessItem->aAdmin == 1) {
        return true;
      }
    }
    return false;
  }
  
}
