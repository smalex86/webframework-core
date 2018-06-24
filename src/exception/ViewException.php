<?php

/*
 * This file is part of the smalex86 package.
 *
 * (c) Alexandr Smirnov <mail_er@mail.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace smalex86\webframework\core\exception;

use smalex86\webframework\core\exception\BaseException;

/**
 * Класс исключения, связанный с работой представления
 *
 * @author Alexandr Smirnov <mail_er@mail.ru>
 */
class ViewException extends BaseException {
  
  public function __construct(string $message = "", int $code = 0, \Throwable $previous = null) {
    parent::__construct($message, $code, $previous);
  }
  
}
