<?php

/*
 * This file is part of the smalex86\webframework\core package.
 *
 * (c) Alexandr Smirnov <mail_er@mail.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace smalex86\webframework\core\user\dataMapper;

use smalex86\webframework\core\{ActiveRecord, Database, DataMapper, Session};
use smalex86\webframework\core\user\activeRecord\User as UserRecord;
use Exception;
use smalex86\webframework\core\exception\DataMapperException;


/**
 * User DataMapper
 *
 * @author Alexandr Smirnov <mail_er@mail.ru>
 */
class User extends DataMapper {
  
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
   * @return \smalex86\webframework\core\user\activeRecord\User
   */
  public function getById(int $id) {
    $query = sprintf('select * from %s where id = %u limit 1', $this->getTableName(), $id);
    $row = $this->database->selectSingleRow($query, __FILE__.':'.__LINE__);
    if ($row && is_array($row)) {
      return new UserRecord(
              $row['id'], 
              $row['u_login'], 
              $row['u_password'], 
              $row['user_group_id'], 
              $row['name_f'], 
              $row['name_m'], 
              $row['name_l'], 
              $row['email'], 
              $row['email_verification_code'], 
              $row['email_verified'], 
              $row['registration_date'], 
              $row['avatar'], 
              $row['phone']
            );
    }
    return null;
  }
  
  /**
   * Получить объект пользователя по логину и паролю
   * @param string $login
   * @param string $password
   * @return UserRecord
   * @throws DataMapperException
   */
  public function getByLoginAndPassword(string $login, string $password)
  {
    $query = sprintf('select * from %s where u_login = "%s" and u_password = md5("%s") limit 1',
            $this->tableName, $login, $password);
    try {
      $row = $this->database->selectSingleRow($query, __FILE__.':'.__LINE__);
      if ($row && is_array($row)) {
        return new UserRecord(
              $row['id'], 
              $row['u_login'], 
              $row['u_password'], 
              $row['user_group_id'], 
              $row['name_f'], 
              $row['name_m'], 
              $row['name_l'], 
              $row['email'], 
              $row['email_verification_code'], 
              $row['email_verified'], 
              $row['registration_date'], 
              $row['avatar'], 
              $row['phone']
            );
      }
    } catch (Exception $ex) {
      $msg = 'Ошибка при выполнении запроса к базе данных: ' . $ex->getMessage();
      $this->logger->error($msg);
      throw new DataMapperException($msg);
    }
    return null;
  }
  
  /**
   * Получает данные о пользователе сохраненном в сессии
   * @return \smalex86\webframework\core\user\activeRecord\User
   */
  public function getActiveUser()
  {
    $userData = $this->session->getData('user');
    $this->logger->debug('userData from session = ' . var_export($userData, true));
    try {
      $user = unserialize($userData['serial']);
      $this->logger->debug('unserialized user = ' . var_export($user, true));
      if ($user === false || get_class($user) != UserRecord::class) {
        return null;
      }
      return $user;
    } catch (Exception $ex) {
      $msg = 'Ошибка при десериализации объекта user из данных сессии: ' . $ex->getMessage();
      $this->logger->error($msg);
      throw new DataMapperException($msg);
    }
    return null;
  }

  public function getList() {
    return;
  }
  
  public function getListByIdList(array $idList) {
    $ids = implode(',', $idList);
    $query = sprintf('select * from %s where id in (%s)', $this->tableName, $ids);
    $result = null;
    try {
      $rows = $this->database->selectMultipleRows($query, __FILE__.':'.__LINE__);
      foreach ($rows as $row) {
        $result[] = new UserRecord(
              $row['id'], 
              $row['u_login'], 
              $row['u_password'], 
              $row['user_group_id'], 
              $row['name_f'], 
              $row['name_m'], 
              $row['name_l'], 
              $row['email'], 
              $row['email_verification_code'], 
              $row['email_verified'], 
              $row['registration_date'], 
              $row['avatar'], 
              $row['phone']
            );
      }
    } catch (Exception $ex) {
      $msg = 'Ошибка при выполнении запроса к базе данных: ' . $ex->getMessage();
      $this->logger->error($msg);
      throw new DataMapperException($msg);
    }
    return $result;
  }

  public function processAction($postData = array()) {
    return;
  }
  
  /**
   * Сохранение объекта в базе данных
   * @param \smalex86\webframework\core\user\activeRecord\User $record
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
  
  /**
   * Сохраняет данные объекта user в сессию
   * @param UserRecord $user
   */
  public function saveToSession(UserRecord $user) 
  {
    $data['serial'] = serialize($user);
    $this->session->setData('user', $data);
  }
  
  /**
   * Очищает данные объекта user в сессии
   */
  public function clearSession()
  {
    $data['serial'] = '';
    $this->session->setData('user', $data);
  }

}
