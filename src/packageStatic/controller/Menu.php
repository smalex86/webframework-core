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
use smalex86\webframework\core\packageStatic\dataMapper\Menu as MenuMapper;

/**
 * Description of Static
 *
 * @author Alexandr Smirnov
 */
class Menu extends Controller {
   
  protected function getRecord() {
    if (!$this->record) {
      $this->record = $this->getMapper()->getByAlias($this->getAlias());
    }
    return $this->record;
  }
  
  protected function getMapper() {
    if (!$this->mapper) {
      $this->mapper = new MenuMapper($this->application->getDatabase(),
              $this->application->getSession());
      $this->mapper->setLogger($this->logger);
    }
    return $this->mapper;
  }
  
  public function getBody() {
    if ($this->getRecord()) {
      $data = $this->getRecord()->getMenu();
    } else {
      $data = 'Запрашиваемое меню не найдено';
    }
    return $data;
  }
  
  public function getTitle() {
    if ($this->getRecord()) {
      return $this->getRecord()->name;
    }
    return 'Меню не найдено';
  }
  
}
