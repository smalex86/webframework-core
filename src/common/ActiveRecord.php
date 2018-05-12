<?php

/*
 * This file is part of the Smalex86\Common package.
 *
 * (c) Alexandr Smirnov <mail_er@mail.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace smalex86\common;

use smalex86\common\Logger;

/**
 * Description of BasicActiveRecord
 *
 * @author Alexandr Smirnov
 */
abstract class ActiveRecord {
  
  protected $logger;

  public function __construct() {
    global $application;
    $this->logger = $application->getLogger();
  }
  
  //abstract static public function newRecord();
  
}
