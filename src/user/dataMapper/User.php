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

use smalex86\webframework\core\{ActiveRecord, DatabasePDO, DataMapper, Session};
use smalex86\webframework\core\user\activeRecord\User as UserRecord;
use Exception;
use smalex86\webframework\core\exception\DataMapperException;


/**
 * User DataMapper
 *
 * @author Alexandr Smirnov <mail_er@mail.ru>
 */
class User extends DataMapper {
  
  public function __construct(DatabasePDO $database, Session $session) {
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
    $query = sprintf('select * from %s where id = :id limit 1', 
            $this->getTableName());
    $params = ['id' => $id];
    try {
      $row = $this->database->selectSingleRow($query, $params);
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
    } catch (Exception $e) {
      $msg = 'Ошибка при выполнении запроса к базе данных: ' . $e->getMessage();
      $this->logger->error($msg);
      throw new DataMapperException($msg);
    }
    return null;
  }
  
  /**
   * Получить объект пользователя по логину и паролю
   * @param string $login
   * @param string $password Пароль в чистом виде, не хэш
   * @return UserRecord
   * @throws DataMapperException
   */
  public function getByLoginAndPassword(string $login, string $password)
  {
    $query = sprintf('select * from %s where u_login = :login and '
            . 'u_password = :password limit 1', $this->tableName);
    $params = ['login' => $login, 'password' => md5($password)];
    try {
      $row = $this->database->selectSingleRow($query, $params);
      if ($row && is_array($row)) {
        $this->logger->debug('user ok');
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
    $data = $this->getParamListForInPrepare($idList);
    $query = sprintf('select * from %s where id in (%s)', $this->tableName, 
            $data['in']);
    $result = null;
    try {
      $rows = $this->database->selectMultipleRows($query, $data['params']);
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
      $query = sprintf('update %s set u_login=:login, u_password=:password, '
              . 'user_group_id=:group_id, name_f=:name_f, name_m=:name_m, '
              . 'name_l=:name_l, email=:email, '
              . 'email_verification_code=:email_verification_code, '
              . 'email_verified=:email_verified, '
              . 'registration_date=:registration_date, avatar=:avatar, '
              . 'phone=:phone '
              . 'where id=:id',
              $this->tableName);
      $params = [
          'login' => $record->login, 
          'password' => $record->password,
          'group_id' => $record->groupId,
          'name_f' => $record->fname,
          'name_m' => $record->mname,
          'name_l' => $record->lname,
          'email' => $record->email,
          'email_verification_code' => $record->emailVerificationCode,
          'email_verified' => $record->emailVerified,
          'registration_date' => $record->registrationDate,
          'avatar' => $record->avatar,
          'phone' => $record->phone,
          'id' => $record->id];
      $this->database->updateSingle($query, $params);
    } else {
      $query = sprintf('insert into %s set u_login=:login, u_password=:password, '
              . 'user_group_id=:group_id, name_f=:name_f, name_m=:name_m, '
              . 'name_l=name_l, email=:email, '
              . 'email_verification_code=:email_verification_code, '
              . 'email_verified=:email_verified, '
              . 'registration_date=:registration_date, avatar=:avatar, '
              . 'phone=:phone',
              $this->tableName);
      $params = [
          'login' => $record->login, 
          'password' => $record->password,
          'group_id' => $record->groupId,
          'name_f' => $record->fname,
          'name_m' => $record->mname,
          'name_l' => $record->lname,
          'email' => $record->email,
          'email_verification_code' => $record->emailVerificationCode,
          'email_verified' => $record->emailVerified,
          'registration_date' => $record->registrationDate,
          'avatar' => $record->avatar,
          'phone' => $record->phone];
      $record->id = $this->database->insertSingle($query, $params);
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
