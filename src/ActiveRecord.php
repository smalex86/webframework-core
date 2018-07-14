<?php

/*
 * This file is part of the smalex86\webframework\core package.
 *
 * (c) Alexandr Smirnov <mail_er@mail.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace smalex86\webframework\core;

use Psr\Log\LoggerAwareInterface;

/**
 * Description of BasicActiveRecord
 *
 * @author Alexandr Smirnov
 */
abstract class ActiveRecord implements LoggerAwareInterface {
  
  /**
   * Константа статуса активной записи
   */
  const STATUS_ACTIVE = 1;
  /**
   * Константа статуса не активной записи
   */
  const STATUS_NOT_ACTIVE = 2;
  /**
   * Константа статуса удаленной записи
   */
  const STATUS_DELETE = 3;
  
  use \Psr\Log\LoggerAwareTrait;

  public function __construct() {
    
  }
  
  //abstract static public function newRecord();
  
}
