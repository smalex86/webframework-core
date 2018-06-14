<?php

/*
 * This file is part of the smalex86 package.
 *
 * (c) Alexandr Smirnov <mail_er@mail.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace smalex86\webframework\core\user;

use smalex86\webframework\core\ActiveRecord;
use smalex86\webframework\core\Server;
use smalex86\webframework\core\user\{UserGroup, UserGroupMapper};

/**
 * User
 *
 * @author Alexandr Smirnov <mail_er@mail.ru>
 */
class User extends ActiveRecord {
  
  /** Идентификатор */
  public $id;
  /** Логин */
  public $login;
  /** Пароль */
  public $password;
  /** Идентификатор группы пользователя */
  public $groupId;
  /** Имя */
  public $fname;
  /** Отчество */
  public $mname;
  /** Фамилия */
  public $lname;
  /** Email */
  public $email;
  /** Код подтверждения email */
  public $emailVerificationCode;
  /** Подтвержден ли email */
  public $emailVerified;
  /** Дата регистрации */
  public $registrationDate;
  /** Ссылка на файл с аватаром */
  public $avatar;
  /** Номер телефона пользователя */
  public $phone;
  
  /**
   * Объект приложения
   * @var Server
   */
  protected $application;
  /**
   * Объект группы
   * @var UserGroup
   */
  protected $group;
  
  /**
   * Конструктор
   * @param int $id
   * @param string $login
   * @param string $password
   * @param int $groupId
   * @param string $fname
   * @param string $mname
   * @param string $lname
   * @param string $email
   * @param string $emailVerificationCode
   * @param int $emailVerified
   * @param string $registrationDate
   * @param string $avatar
   * @param string $phone
   */
  public function __construct($id, $login, $password, $groupId, $fname, $mname, $lname, $email, 
          $emailVerificationCode, $emailVerified, $registrationDate, $avatar, $phone) {
    parent::__construct();
    $this->id = $id;
    $this->login = $login;
    $this->password = $password;
    $this->groupId = $groupId;
    $this->fname = $fname;
    $this->mname = $mname;
    $this->lname = $lname;
    $this->email = $email;
    $this->emailVerificationCode = $emailVerificationCode;
    $this->emailVerified = $emailVerified;
    $this->registrationDate = $registrationDate;
    $this->avatar = $avatar;
    $this->phone = $phone;
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
   * Возвращает объект группы пользователя
   * @param Server $application
   * @return UserGroup
   */
  public function getUserGroup(): UserGroup
  {
    if (!$this->id) {
      return new UserGroup(null, null, null, null);
    }
    if (!$this->group) {
      if (!$this->application) {
        return null;
      } else {
        $userGroupMapper = new UserGroupMapper($this->application->getDatabase(), 
                $this->application->getSession());
        $this->group = $userGroupMapper->getById($this->groupId);
        if ($this->group) {
          $this->group->setApplication($this->application);
        }
      }
    }
    return $this->group;
  }
  
}
