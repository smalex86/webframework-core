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
use Psr\Log\LoggerAwareTrait;
use smalex86\webframework\core\Server;

/**
 * Description of Controller
 *
 * @author Alexandr Smirnov
 */
abstract class Controller implements LoggerAwareInterface {
  
  use LoggerAwareTrait;
  
  /**
   * Список классов представлений для различных action
   * Набор данных вида: ['action' => 'viewClass']
   * 
   * @var array 
   */
  protected $viewList = [];
  
  protected $mapper = null;
  protected $record = null;
  
  protected $mapperClass = '';

  protected $alias = '';
  /**
   * Объект приложения
   * @var Server
   */
  protected $application;

  public function __construct(Server $application, $alias = '') {
    $this->application = $application;
    if ($alias) {
      $this->alias = $alias;
    }
  }
  
  /**
   * Задать список классов представлений для различных action
   * Перезаписывает настройки представлений
   * Набор данных вида: ['action' => 'viewClass']
   * 
   * @param array $viewList Список представлений
   */
  public function mergeViewList(array $viewList) {
    $this->viewList = array_merge($this->viewList, $viewList);
  }
  
  /**
   * Метод возвращает алиас контроллера
   */
  public function getAlias() {
    return $this->alias;
  }

  /**
   * Метод возвращает DataMapper контроллера
   */
  abstract protected function getMapper();

  /**
   * Метод возвращает ActiveRecord контроллера
   */
  abstract protected function getRecord();


  /**
   * Метод возвращающий заголовок страницы\компонента\меню, который не входит в состав body
   */
  abstract public function getTitle();
  
  /**
   * Метод возращающий содержимое
   */
  abstract public function getBody();
  
}
