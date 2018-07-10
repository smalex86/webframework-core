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

  static public $varArray = [];
  
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
  
  
  static public function getScriptName() {
    return $_SERVER['SCRIPT_NAME'];
  }
  
  /**
   * Инициализация списка переменных для подстановок
   * @param array $config массив данных из конфигурационного файла linkVariable.php
   * @param \Psr\Log\LoggerInterface $logger
   */
  static public function variableArrayInit(array $config, \Psr\Log\LoggerInterface $logger) {
    if (empty($config)) {
      return ;
    }
    self::$varArray = [];
    foreach ($config as $varInitItem) {
      try {
        if ($varInitItem['value']) {
          self::$varArray[$varInitItem['name']] = $varInitItem['value'];
        } else {
          if ($varInitItem['init']['static']) {
            $method = $varInitItem['init']['method'];
            self::$varArray[$varInitItem['name']] = 
                    $varInitItem['init']['class']::$method();
          } else {
            self::$varArray[$varInitItem['name']] = 
                    $varInitItem['init']['class']->$varInitItem['init']['method']();
          }
        }
      } catch (\Exception $e) {
        $msg = $e->getMessage() . ', file = ' . $e->getFile() . ', line = ' . $e->getLine();
        $logger->warning($msg);
      }
    }
  }
  
  /**
   * Возвращает значение переменной, если таковая существует, либо null
   * @param string $varName
   * @return string|null
   */
  static public function getVariable(string $varName) {
    if (!isset(self::$varArray) || empty(self::$varArray)) {
      return null;
    }
    $result = null;
    if (array_key_exists($varName, self::$varArray)) {
      $result = self::$varArray[$varName];
    }
    return $result;
  }
  
  /**
   * Выполнить замену переменных в строке
   * @param string $input входная строка
   * @return string строка на выходе
   */
  static public function replaceVariables(string $input) {
    if (!isset(self::$varArray) || empty(self::$varArray) || !$input) {
      return $input;
    }
    $output = $input;
    foreach (self::$varArray as $key=>$value) {
      $output = preg_replace(sprintf('/<<%s>>/', $key), $value, $output);
    }
    return $output;
  }
   
}