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
 * @author Alexandr Smirnov
 */
class Page extends Controller {
   
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
    if ($this->getRecord()) {
      $data = '<div class="page-header">';
      $data .= sprintf('<h1>%s</h1>', $this->getRecord()->pageName);
      $data .= '</div>';
      $data .= $this->getRecord()->pageText;
    } else {
      $data = '404 - Запрашиваемая страница не найдена';
    }
    return $data;
  }
  
  public function getTitle() {
    if ($this->getRecord()) {
      $title = $this->getRecord()->pageTitle;
    } else {
      $title = '404 Страница не найдена';
    }
    return $title;
  }
  
}
