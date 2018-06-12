<?php

/*
 * This file is part of the smalex86 package.
 *
 * (c) Alexandr Smirnov <mail_er@mail.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace smalex86\webframework\core\controller\component;

use smalex86\webframework\core\Controller;
use smalex86\webframework\core\model\StaticComponentMapper;

/**
 * Description of Static
 *
 * @author Alexandr Smirnov
 */
class StaticController extends Controller {
   
  protected function getRecord() {
    if (!$this->record) {
      $this->record = $this->getMapper()->getByAlias($this->getAlias());
    }
    return $this->record;
  }
  
  protected function getMapper() {
    if (!$this->mapper) {
      $this->mapper = new StaticComponentMapper($this->application->getDatabase(),
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
