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
use smalex86\webframework\core\{Server, View};
use smalex86\webframework\core\exception\ControllerException;

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
  protected $configViewList = [];
  /**
   * Список объектов представлений
   * 
   * @var View[]
   */
  protected $viewList = [];
  
  protected $mapper = null;
  protected $record = null;
  
  protected $mapperClass = '';
  /**
   * Атрибут 'page' из uri
   * @var string
   */
  protected $alias;
  /**
   * Атрибут 'action' из uri
   * @var string
   */
  protected $action;
  /**
   * Объект приложения
   * @var Server
   */
  protected $application;

  public function __construct(Server $application, $alias = '', $action = 'view') {
    $this->application = $application;
    $this->alias = $alias;
    $this->action = $action;
  }
  
  /**
   * Задать список классов представлений для различных action
   * Перезаписывает настройки представлений
   * Набор данных вида: ['action' => 'viewClass']
   * 
   * @param array $viewList Список представлений
   */
  public function mergeViewList(array $viewList) {
    $this->configViewList = array_merge($this->configViewList, $viewList);
  }
  /**
   * Метод возвращает алиас контроллера
   */
  public function getAlias() {
    return $this->alias;
  }
  /**
   * Возвращает значение атрибута action
   * @return string
   */
  public function getAction() {
    return $this->action;
  }
  /**
   * Метод возвращает объект представления по его имени
   * 
   * @param string $name
   * @return View
   * @throws ControllerException
   */
  protected function getView(string $name): View
  {
    if (isset($this->viewList[$name])) {
      return $this->viewList[$name];
    } else {
      if (isset($this->configViewList[$name])) {
        try {
          $view = new $this->configViewList[$name]();
          $view->setLogger($this->logger);
          $this->viewList[$name] = $view;
          return $view;
        } catch (\Exception $ex) {
          $msg = sprintf('View initialization error with name "%s": %s', $name, $ex->getMessage());
          $this->logger->error($msg);
          throw new ControllerException($msg);
        }
      } else {
        $msg = sprintf('View initialization error: Not found view with name "%s"', $name);
        $this->logger->error($msg);
        throw new ControllerException($msg);
      }
    }
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
