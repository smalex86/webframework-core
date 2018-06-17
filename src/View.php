<?php

/*
 * This file is part of the smalex86 package.
 *
 * (c) Alexandr Smirnov <mail_er@mail.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace smalex86\webframework\core;

use Psr\Log\LoggerAwareInterface;

/**
 * Basic class of View for MVC
 *
 * @author Alexandr Smirnov <mail_er@mail.ru>
 */
abstract class View implements LoggerAwareInterface {
  
  use \Psr\Log\LoggerAwareTrait;
  
  /**
   * Метод формирует по входным параметрам тело представления на основе шаблона
   */
  abstract public function getView(array $data): string;
  /**
   * Метод формирует по входным параметрам заголовок представления
   */
  abstract public function getTitle(array $data): string;
  
}
