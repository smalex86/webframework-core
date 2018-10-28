<?php

/*
 * This file is part of the smalex86 package.
 *
 * (c) Alexandr Smirnov <mail_er@mail.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace smalex86\webframework\core\user\controller;

use smalex86\webframework\core\Controller;
use smalex86\webframework\core\user\dataMapper\User as UserMapper;

/**
 * User controller
 *
 * @author Alexandr Smirnov <mail_er@mail.ru>
 */
class User extends Controller {
  
  protected $configViewList = [
      'view' => 'smalex86\\webframework\\core\\user\\view\\Page',
      'login' => 'smalex86\\webframework\\core\\user\\view\\Login',
      'info' => 'smalex86\\webframework\\core\\user\\view\\Info'
  ];
  
  protected function getMapper() {
    if (!$this->mapper) {
      $this->mapper = new UserMapper($this->application->getDatabase(),
              $this->application->getSession());
      $this->mapper->setLogger($this->logger);
    }
    return $this->mapper;
  }

  protected function getRecord() {
    if (!$this->record) {
      $this->record = $this->getMapper()->getActiveUser();
    }
    return $this->record;
  }

  public function getBody() {
    if (isset($this->configViewList[$this->action])) {
      switch ($this->action) {
        case 'login':
          if (!$this->getRecord()) {
            $data = $this->getView()->getView([]);
          } else {
            $data = '';
          }
          break;
        case 'info':
          $inputData = [];
          if ($this->getRecord()) {
            $inputData = [
                'name' => $this->getRecord()->fname . ' ' . $this->getRecord()->lname
            ];
          }
          $data = $this->getView()->getView($inputData);
          break;
        case 'view':
          
          break;
      }
      
//      $data = $this->getView($this->action)->getView([
//          'title' => $this->getRecord()->title, 
//          'body' => $this->getRecord()->text]);
    } else {
      $data = $this->getView('error404')->getView([]);
    }
    return $data;
  }

  public function getTitle() {
    if (isset($this->configViewList[$this->action]) && $this->getRecord()) {
      $title = $this->getView($this->action)->getTitle(['title' => $this->getRecord()->title]);
    } else {
      $title = $this->getView('error404')->getTitle([]);
    }
    return $title;
  }

  
  public function processAction(array $data) {
    if (is_array($data)) {
      $this->logger->debug('data = ' . var_export($data, true));
      foreach ($data as $field=>$value) {
        switch ($field) {
          case 'submitLogin': 
            if (isset($data['ulogin'], $data['upassword'])) {
              $user = $this->getMapper()->getByLoginAndPassword($data['ulogin'], 
                      $data['upassword']);
              if ($user) {
                $this->getMapper()->saveToSession($user);
                $this->session->setPostMessageToSession('Вы выполнили авторизацию под учетной '
                        . 'записью с логином ' . $user->login, $this->session::ALERT_SUCCESS);
              } else {
                $this->session->setPostMessageToSession('Некорректные данные для авторизации', 
                        $this->session::ALERT_DANGER);
              }
            } else {
              $this->session->setPostMessageToSession('Не введен логин или пароль', 
                      $this->session::ALERT_DANGER);
            }
            break;
//          case 'submitRegistration':
//            $this->application->setPostMessageInSession('', $this->userRegistration($data), $this->application->getSmartPost()); 
//            break;
          case 'submitExit':
            $user = $this->getMapper()->getActiveUser();
            if ($user) {
              $this->getMapper()->clearSession();
              $this->session->setPostMessageToSession('Выполнен выход для учетной записи '
                        . 'с логином ' . $user->login, $this->session::ALERT_SUCCESS);
            } else {
              $this->session->setPostMessageToSession('Не найдена активная учетная запись',
                        $this->session::ALERT_INFO);
            }
            break;
//          case 'changeName':
//            $this->application->setPostMessageInSession('', $this->userChangeName($data['changeName']), $this->application->getSmartPost()); 
//            break;
//          case 'changeCity':
//            $this->application->setPostMessageInSession('', $this->userChangeCity($data['changeCity']), $this->application->getSmartPost());
//            break;
//          case 'changeEmail':
//            $this->application->setPostMessageInSession('', $this->userChangeEmail($data['changeEmail']), $this->application->getSmartPost());
//            break;
//          case 'changePhone':
//            $this->application->setPostMessageInSession('', $this->userChangePhone($data['changePhone']), $this->application->getSmartPost());
//            break;
//          case 'changePassword':
//            $this->application->setPostMessageInSession('', $this->userChangePassword($data['changePassword']), $this->application->getSmartPost()); 
//            break;
//          case 'changeAvatar':
//            $this->application->setPostMessageInSession('', $this->userChangeAvatar($data['changeAvatar']), $this->application->getSmartPost()); 
//            break;
//          case 'changeEmailStatus':
//            $this->application->setPostMessageInSession('', $this->userChangeEmailStatus($data['changeEmailStatus']), $this->application->getSmartPost());
//            break;
//          case 'changePhoneStatus':
//            $this->application->setPostMessageInSession('', $this->userChangePhoneStatus($data['changePhoneStatus']), $this->application->getSmartPost());
//            break;
        }
      }			
    }
  }

  public function processAjax(array $getData, array $postData) {
    return ;
  }

}
