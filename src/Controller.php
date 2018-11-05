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
use smalex86\webframework\core\{Server, View, Session, Database};
use smalex86\webframework\core\exception\ControllerException;

/**
 * Description of Controller
 *
 * @author Alexandr Smirnov
 */
abstract class Controller implements LoggerAwareInterface {
  
  use LoggerAwareTrait;
  
  /**
   * Работа идет через ajax
   * @var bool
   */
  public $ajax = false;
  
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
  /**
   * Класс DataMapper контроллера
   * @var string
   */
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
  /**
   * Объект сессии
   * @var Session
   */
  protected $session;
  /**
   * Объект базы данных
   * @var Database
   */
  protected $database;
  /**
   * Данные массива $_GET
   * @var array
   */
  protected $getData = [];
  /**
   * Данные массива $_POST
   * @var array
   */
  protected $postData = [];

  public function __construct(Server $application, $alias = '', $action = 'view') {
    $this->application = $application;
    $this->session = $application->getSession();
    $this->database = $application->getDatabase();
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
   * Передать в контроллер данные массива $_GET
   * @param array $getData
   */
  public function setGetData(array $getData) 
  {
    $this->getData = $getData;
  }
  
  /**
   * Передать в контроллер данные массива $_POST
   * @param array $postData
   */
  public function setPostData(array $postData)
  {
    $this->postData = $postData;
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
   * Получить значение параметра из массива _GET
   * @param string $name
   * @return string|null
   */
  public function getParamFromGetData(string $name) {
    if (!empty($this->getData) && isset($this->getData[$name])) {
      return $this->getData[$name];
    }
    return null;
  }
  /**
   * Метод возвращает объект представления по его имени
   * 
   * @param string $name
   * @return View
   * @throws ControllerException
   */
  protected function getView(string $name = ''): View
  {
    if (!$name) {
      $name = $this->action;
    }
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
   * Выполнить обработку запроса ajax
   */
  abstract public function processAjax(array $getData, array $postData);
  
  /**
   * Выполнить обработку пост-данных
   */
  abstract public function processAction(array $data);

  /**
   * Метод возвращающий заголовок страницы\компонента\меню, который не входит в состав body
   */
  abstract public function getTitle();
  
  /**
   * Метод возращающий содержимое
   */
  abstract public function getBody();
  
}
