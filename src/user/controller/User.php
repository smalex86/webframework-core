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
            $data = $this->getView($this->action)->getView([]);
          } else {
            $data = '';
          }
          break;
        case 'info':
          
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

}
