<?php

/*
 * This file is part of the smalex86 package.
 *
 * (c) Alexandr Smirnov <mail_er@mail.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace smalex86\wfCore\controller\Component;

use smalex86\wfCore\Controller;
use smalex86\wfCore\model\StaticComponentMapper;

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
      $this->mapper = new StaticComponentMapper;
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
