<?php

/*
 * This file is part of the smalex86 package.
 *
 * (c) Alexandr Smirnov <mail_er@mail.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace smalex86\webframework\core\packageStatic\controller;

use smalex86\webframework\core\Controller;
use smalex86\webframework\core\packageStatic\dataMapper\Page as PageMapper;

/**
 * Description of Page
 *
 * @author Alexandr Smirnov <mail_er@mail.ru>
 */
class Page extends Controller {
  
  protected $configViewList = [
      'error404' => 'smalex86\\webframework\\core\\packageStatic\\view\\Page404',
      'view' => 'smalex86\\webframework\\core\\packageStatic\\view\\Page'
  ];

  protected function getRecord() {
    if (!$this->record) {
      $this->record = $this->getMapper()->getByAlias($this->getAlias());
    }
    return $this->record;
  }
  
  protected function getMapper() {
    if (!$this->mapper) {
      $this->mapper = new PageMapper($this->application->getDatabase(),
              $this->application->getSession());
    }
    return $this->mapper;
  }
  
  public function getBody() {
    if (isset($this->configViewList[$this->action]) && $this->getRecord()) {
      $data = $this->getView($this->action)->getView([
          'title' => $this->getRecord()->title, 
          'body' => $this->getRecord()->text]);
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
