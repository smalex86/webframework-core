<?php

/*
 * This file is part of the smalex86\webframework\core package.
 *
 * (c) Alexandr Smirnov <mail_er@mail.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace smalex86\webframework\core\user;

use smalex86\webframework\core\{ActiveRecord, Database, DataMapper, Session};

/**
 * UserMapper
 *
 * @author Alexandr Smirnov <mail_er@mail.ru>
 */
class UserMapper extends DataMapper {
  
  public function __construct(Database $database, Session $session) {
    parent::__construct($database, $session);
    $this->tableName = 'core_user';
  }

  protected function beforeInsert() {
    return;
  }

  protected function getFields() {
    return;
  }
  
  /**
   * Возвращает данные о пользователе по его идентификатору из бд
   * @param int $id
   * @return \smalex86\webframework\core\User
   */
  public function getById(int $id) {
    $query = sprintf('select * from %s where id = %u limit 1', $this->getTableName(), $id);
    $row = $this->database->selectSingleRow($query, __FILE__.':'.__LINE__);
    if ($row && is_array($row)) {
      return new User($row['id'], $row['u_login'], $row['u_password'], $row['user_group_id'], 
              $row['name_f'], $row['name_m'], $row['name_l'], $row['email'], 
              $row['email_verification_code'], $row['email_verified'], $row['registration_date'], 
              $row['avatar'], $row['phone']);
    }
    return null;
  }
  
  /**
   * Получает данные о пользователе сохраненном в сессии
   * @return \smalex86\webframework\core\User
   */
  public function getActiveUser() {
    $userSessionData = $this->session->getData('user');
    if (isset($userSessionData['id'])) {
      return $this->getById($userSessionData['id']);
    }
    return null;
  }

  public function getList() {
    return;
  }

  public function processAction($postData = array()) {
    return;
  }
  
  /**
   * Сохранение объекта в базе данных
   * @param User $record
   * @return 
   */
  public function save(ActiveRecord $record) {
    if ($record->id) {
      $query = sprintf('update %s set u_login="%s", u_password="%s", user_group_id=%u,'
              . 'name_f="%s", name_m="%s", name_l="%s", email="%s", email_verification_code="%s",'
              . 'email_verified=%u, registration_date="%s", avatar="%s", phone="%s" '
              . 'where id=%u',
              $this->tableName,
              $record->login, 
              $record->password,
              $record->groupId,
              $record->fname,
              $record->mname,
              $record->lname,
              $record->email,
              $record->emailVerificationCode,
              $record->emailVerified,
              $record->registrationDate,
              $record->avatar,
              $record->phone,
              $record->id);
      $this->database->updateSingle($query, __FILE__.':'.__LINE__);
    } else {
      $query = sprintf('insert into %s set u_login="%s", u_password="%s", user_group_id=%u,'
              . 'name_f="%s", name_m="%s", name_l="%s", email="%s", email_verification_code="%s",'
              . 'email_verified=%u, registration_date="%s", avatar="%s", phone="%s"',
              $this->tableName,
              $record->login, 
              $record->password,
              $record->groupId,
              $record->fname,
              $record->mname,
              $record->lname,
              $record->email,
              $record->emailVerificationCode,
              $record->emailVerified,
              $record->registrationDate,
              $record->avatar,
              $record->phone);
      $record->id = $this->database->insertSingle($query, __FILE__.':'.__LINE__);
    }
    return null;
  }

}
