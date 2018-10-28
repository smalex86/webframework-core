<?php

/*
 * This file is part of the Smalex86 package.
 *
 * (c) Alexandr Smirnov <mail_er@mail.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace smalex86\webframework\core\session\controller;

use smalex86\webframework\core\Controller;

/**
 * Controller for Session object
 *
 * @author Alexandr Smirnov <mail_er@mail.ru>
 */
class Session extends Controller {
  
  protected function getMapper() {
    return ;
  }

  protected function getRecord() {
    return ;
  }

  public function getBody() {
    return ;
  }

  public function getTitle() {
    return ;
  }

  public function processAction(array $data) {
    return ;
  }

  public function processAjax(array $getData, array $postData) {
    $this->logger->debug('get = ' . var_export($getData, true) . ', post = ' 
            . var_export($postData, true));
    switch ($getData['action']) {
      case 'delPostMsg':
        if (isset($getData['id']) && is_numeric($getData['id'])) {
          $this->session->delPostMessageFromSession($getData['id']);
        }
        break;
      default:
        break;
    }
  }

}
