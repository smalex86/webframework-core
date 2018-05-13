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

/**
 * Description of FunctionObject
 *
 * @author Alexandr Smirnov
 */
class FunctionList {

  /**
   * вычисление протокола сервера
   * 
   * @return string
   */
  static public function getServerProtocol() {
    if (!empty($_SERVER['HTTPS']) && 'off' !== strtolower($_SERVER['HTTPS'])) {
      return 'https://';
    } else {
      return 'http://';
    }
  }

  /**
   * Выводит значение хоста сервера без протокола и uri
   * 
   * @return string
   */
  static public function getServerHost() {
    return $_SERVER['HTTP_HOST'];
  }

  /**
   * Формирует и возвращает текущий адрес страницы вида <протокол>://<хост>/<uri>
   * 
   * @return string
   */
  static public function getCurrentUrl() {
    return self::getServerProtocol() . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
  }
   
}