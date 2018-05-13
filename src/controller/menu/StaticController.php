<?php

/*
 * This file is part of the smalex86 package.
 *
 * (c) Alexandr Smirnov <mail_er@mail.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace smalex86\webframework\core\controller\Menu;

use smalex86\webframework\core\Controller;
use smalex86\webframework\core\model\StaticMenuMapper;

/**
 * Description of Static
 *
 * @author Alexandr Smirnov
 */
class StaticController extends Controller {
  
  public function __construct($alias = '') {
    parent::__construct($alias);
  }
   
  protected function getRecord() {
    if (!$this->record) {
      $this->record = $this->getMapper()->getByAlias($this->getAlias());
    }
    return $this->record;
  }
  
  protected function getMapper() {
    if (!$this->mapper) {
      $this->mapper = new StaticMenuMapper;
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
