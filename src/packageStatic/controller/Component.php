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
use smalex86\webframework\core\packageStatic\dataMapper\Component as ComponentMapper;

/**
 * Description of Component
 *
 * @author Alexandr Smirnov
 */
class Component extends Controller {
   
  protected function getRecord() {
    if (!$this->record) {
      $this->record = $this->getMapper()->getByAlias($this->getAlias());
    }
    return $this->record;
  }
  
  protected function getMapper() {
    if (!$this->mapper) {
      $this->mapper = new ComponentMapper($this->application->getDatabase(),
              $this->application->getSession());
    }
    return $this->mapper;
  }
  
  public function getBody() {
    if ($this->getRecord()) {
      $data = $this->getRecord()->text;
    } else {
      $data = 'Запрашиваемый компонент не найден';
    }
    return $data;
  }
  
  public function getTitle() {
    if ($this->getRecord()) {
      return $this->getRecord()->name;
    }
    return 'Компонент не найден';
  }
  
}
