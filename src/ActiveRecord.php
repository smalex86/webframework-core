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

  /**
   * DataMapper
   * @var DataMapper
   */
  protected $dataMapper = null;
  
  /**
   * Печать объекта
   * @return string
   */
  public function __toString() {
    $keyValue = [];
    foreach ($this as $key => $value) {
      $keyValue[] = $key . '=' . var_export($value, true);
    }
    return sprintf('Object of %s(%s)', get_class($this), implode(';', $keyValue));
  }

  public function setDataMapper(DataMapper $dataMapper) {
    $this->dataMapper = $dataMapper;
  }

}
