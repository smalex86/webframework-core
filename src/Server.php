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

use ArrayObject;
use Exception;
use Psr\Log\LoggerAwareInterface;
use smalex86\webframework\core\{Controller, Database, Session};
use smalex86\webframework\core\exception\ControllerException;

/**
 * Description of Server
 *
 * @author Alexandr Smirnov
 */
class Server implements LoggerAwareInterface {
  
  use \Psr\Log\LoggerAwareTrait;
  
  /** Список типов контроллеров */
  const CONTROLLER_TYPES = ['page', 'menu', 'component'];
  /** Название контроллера для статической страницы */
  const CONTROLLER_STATIC_PAGE = 'staticPage';
  /** Название контроллера для статического компонента */
  const CONTROLLER_STATIC_COMPONENT = 'staticComponent';
  /** Название контроллера для статического меню */
  const CONTROLLER_STATIC_MENU = 'staticMenu';
  
  /**
   * Настройки
   * @var ArrayObject
   */
  protected $config;
  /**
   * Поле для хранения указателя на объект сессии
   * @var Session
   */  
  protected $session = null;
  /**
   * поле для хранения указателя на объект для работы с базой данных
   * @var Database
   */
  protected $database = null;
  /**
   * controller текущей страницы, из него осуществляется доступ к странице
   * @var Controller 
   */
  protected $pageController = null; 
  /**
   * массив контроллеров меню страницы
   * @var Controller[] 
   */
  protected $menuControllers = [];
  /**
   * массив контроллеров компонентов страницы
   * @var Controller[] 
   */
  protected $componentControllers = array();
  /**
   * хранит namespace, поле необходимо чтобы после
   * переопределения методов корректно работали ссылки
   * @var string
   */
  protected $namespace = '';
  
  public function __construct(ArrayObject $config, Database $database) {
    $this->namespace = __NAMESPACE__;
    $this->config = $config;
    $this->database = $database;
  }
  
  /**
   * Возвращает объект логгера
   * @return smalex86\logger\SimpleLogger
   */
  public function getLogger() {
    return $this->logger;
  }
  
  /**
   * Возвращает объект для работы с сессиями
   * @return Session
   */
  public function getSession() {
    if (!$this->session) {
      $this->session = new Session();
      $this->session->setLogger($this->logger);
      if (!$this->session) {
        $msg = 'Не удалось обратиться к объекту Session';
        $this->logger->error($msg);
        return null;
      }
    }
    return $this->session;
  }
  
  /**
   * Возвращает объект соединения с базой данных, при создании объекта выполняется 
   * попытка подключения к базе данных
   * @return Database
   */
  public function getDatabase() {
    return $this->database;
  }
  
  /**
   * Метод формирует алиас текущей страницы по значениям параметров GET
   * В базовом варианте обрабатывается два варианта:
   *  1. Возвращается $_GET['page'] если он существует
   *  2. Возвращается 'pageStatic' если $_GET['page'] не существует
   * @return string
   */
  protected function getPageAlias() {
    if (isset($_GET['page'])) {
      return $_GET['page'];
    } else {
      return 'main';
    }
  }
  
  /** 
   * Возвращает название сайта
   * @return string
   */
  public function getSiteName() {
    if (isset($this->config->site['name'])) {
      return $this->config->site['name'];
    }
    return '';
  }
  
  /**
   * Метод формирует действие текущей страницы по значениям параметров GET
   * В базовом варианте обрабатывается два варианта:
   *  1. Возвращается $_GET['action'] если он существует
   *  2. Возвращается 'view' если $_GET['action'] не существует
   * @return string
   */
  protected function getPageAction() {
    if (isset($_GET['action'])) {
      return $_GET['action'];
    } else {
      return 'view';
    }
  }

  /**
   * Метод выполняет поиск контроллера от типа, алиаса и действия
   * @param string $type
   * @param string $name
   * @param string $action
   * @return Controller возвращается объект контроллера
   */
  protected function getController($type, $name, $action = 'view'): Controller
  {
    return $this->getControllerFromConfigList($type, $name, $action);
  }
  
  /**
   * Метод выполняет поиск и создание объекта pageController по параметрам строки GET
   * @return Controller
   */
  protected function getPageController() {
    if (!$this->pageController) {
      $this->pageController = $this->getController('page', $this->getPageAlias(), 
              $this->getPageAction());
    } 
    return $this->pageController;
  }
  
  /**
   * Возвращает заголовок страницы
   * @return string
   */
  public function getPageTitle() {
    return $this->getPageController()->getTitle() . ' - ' . $this->config->site['name'];
  }
  
  /**
   * Возвращает содержимое страницы
   * @return string
   */
  public function getPageContent() {
    return $this->getPageController()->getBody();
  }
  
  /**
   * Универсальный метод для получения содержимого компонентов, меню и прочих объектов страницы
   * @param string $type тип компонента: component, menu
   * @param string $alias алиас требуемого компонента
   * @param string $action параметр для идентификации представления/действия компонента
   * @param array of string $pages обозначает на страницах с какими алиасами выводить компонент
   * @param boolean $inverse если true, то будет выводить компонент на всех страницах кроме $pages
   * @return string
   */
  protected function getAnyComponent($type, $alias, $action = 'view', $pages = array(), 
          $inverse = false) {
    //проверить введен ли массив страниц
    if ($pages) {
      // если введен, то проверить не входит ли текущая страница в этот массив + inverse
      if (!(in_array($this->getPageAlias(), $pages) xor $inverse)) {
        return '';
      }
    }
    // если уже существует массив в контроллерами компонентов, выполнить поиск среди них
    $componentController = null;
    if (count($this->componentControllers)) {
      foreach ($this->componentControllers as $controller) {
        // если найден контроллер с таким алиасом, то запоминает его и выходим из цикла
        if ($controller->getAlias() == $alias) {
          $componentController = $controller;
          break;
        }
      }
    } 
    // если контроллер в массиве не найден, то вызываем поиск контроллера
    if (!$componentController) {
      $componentController = $this->getController($type, $alias, $action);
      // если контроллер найден и создан, то добавляем его в массив контроллеров компонентов
      if ($componentController) {
        $this->componentControllers[] = $componentController;
      }
    }
    // проверяем найден ли контроллер, если нет - на выход, если да - запросить содержимое
    if ($componentController) {
      return $componentController->getBody();
    } else {
      return '';
    }
  }
  
  /**
   * Метод возвращает содержимое компонента
   * @param string $alias алиас требуемого компонента
   * @param string $action параметр для идентификации представления/действия компонента
   * @param array of string $pages обозначает на страницах с какими алиасами выводить компонент
   * @param boolean $inverse если true, то будет выводить компонент на всех страницах кроме $pages
   * @param int $position дополнительный параметр компонента
   * @return string
   */
  public function getComponent($alias, $action = 'view', $pages = array(), $inverse = false) {
    return $this->getAnyComponent('component', $alias, $action, $pages, $inverse);
  }
  
  /**
   * Метод возвращает содержимое меню
   * @param string $alias алиас требуемого компонента
   * @param array of string $pages обозначает на страницах с какими алиасами выводить компонент
   * @param boolean $inverse если true, то будет выводить компонент на всех страницах кроме $pages
   * @return type
   */
  public function getMenu($alias, $pages = array(), $inverse = false) {
    return $this->getAnyComponent('menu', $alias, 'view', $pages, $inverse);
  }
  
  /**
   * Метод выполняет команды необходимые для запуска построения страницы.
   * Вынесен за пределы конструктора, потому что многие объекты при создании обращаются к 
   * объекту Server application, который уже должен быть создан на момент их вызова
   */
  public function startPageBuilder() {
    $this->getPageController(); // поиск и создание контроллера страниц
  }
  
  /**
   * Метод для обработки пост-данных
   * @return boolean
   */
  public function startActionManager() {
    if ($_POST) {
      foreach ($_POST as $field => $value) {
        $this->logger->debug('Данные = '.var_export($value, true));
        if (is_array($value)) {
          // подключение требуемой библиотеки
          $className = $this->namespace . '\\' . $field;
          $this->logger->debug('Класс = '.$className);
          $this->logger->debug('class exists = '.class_exists($className)); 
          if (class_exists($className)) {
            $obj = new $className;
            if ($obj && method_exists($obj, 'processAction')) {
              $obj->processAction($value);
            } else {
              $this->logger->warning('Класс '.$className.
                      ' не имеет метода processAction, данные ('.var_export($value, true).
                      ') не будут обработаны');
            }
          } else {
            $this->logger->warning('Класс '.$className.
                    ' не найден, данные ('.var_export($value, true).
                    ') не будут обработаны');
          }
        }
      }
      // чтобы снова не вызывался обработчик массива пост, очищаем его
      header("Location: http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
    }
    // проверка на наличие данных оставленных после неудачной пост обработки
    $this->getSession()->checkPostData(); 
    return FALSE;
  }
  
  /**
   * Получить настройки контроллеров по типу
   * Если тип не указан вернуть все настройки контроллеров
   * 
   * @param string $type
   * @return array
   * @throws ControllerException
   */
  protected function getControllerConfigList(string $type = ''): array
  {
    if (!isset($this->config->controller) || empty($this->config->controller)) {
      $msg = 'Empty controller list config';
      $this->logger->error($msg);
      throw new ControllerException($msg);
    }
    $result = [];
    if ($type == '') {
      $result = $this->config->controller;
    } else if ($type && in_array($type, self::CONTROLLER_TYPES)) {
      foreach ($this->config->controller as $controller) {
        if ($controller['type'] == $type) {
          $result[] = $controller;
        }
      }
    }
    return $result;
  }
  
  /**
   * Получить объект контроллера
   * 
   * @param string $type
   * @param string $name Атрибут 'page' из uri
   * @param string $action Атрибут 'action' из uri
   * @return Controller
   * @throws ControllerException
   */
  protected function getControllerFromConfigList(string $type, string $name, 
          string $action): Controller
  {
    $controllerConfigList = $this->getControllerConfigList($type);
    if (!$controllerConfigList || empty($controllerConfigList)) {
      $msg = 'Not found controller configs by type "' . $type . '"';
      $this->logger->error($msg);
      throw new ControllerException($msg);
    }
    $controllerConfig = $this->findControllerConfigIn($controllerConfigList, $name, $action);
    $alias = '';
    // если для заданного имени не найден контроллер, то получить контроллер для вывода
    // статического содержимого
    if (!$controllerConfig) {
      $alias = $name;
      $name = $this->getControllerStaticName($type);
      $controllerConfig = $this->findControllerConfigIn($controllerConfigList, $name, $action);
    }
    try {
      if ($action == '') {
        $action = 'view';
      }
      $controller = new $controllerConfig['class']($this, $alias, $action);
      $controller->setLogger($this->logger);
      $controller->mergeViewList($controllerConfig['action']);
      return $controller;
    } catch (Exception $e) {
      $msg = 'Error initialization controller with class = ' . $controllerConfig['class']
              . ', error: ' . $e->getMessage();
      $this->logger->error($msg);
      throw new ControllerException($msg);
    }
  }
  
  /**
   * Поиск в массиве настроек контроллеров нужного контроллера
   * 
   * @param array $configItems Массив настроек контроллеров
   * @param string $name Название контроллера
   * @param string $action Атрибут действия
   * @return array
   */
  private function findControllerConfigIn(array $configItems, string $name, string $action)
  {
    $result = [];
    foreach ($configItems as $item) {
      if ($item['name'] == $name) {
        foreach ($item['action'] as $itemAction=>$view) {
          if ($itemAction == $action) {
            $result = $item;
            break;
          }
        }
      }
      if ($result) {
        break;
      }
    }
    return $result;
  }
  
  /**
   * Возвращает имя контроллера для вывода статического содержимого
   * 
   * @param string $type Тип контроллера
   * @return string
   */
  private function getControllerStaticName(string $type): string
  {
    switch ($type) {
      case 'page':
        $name = self::CONTROLLER_STATIC_PAGE;
        break;
      case 'component':
        $name = self::CONTROLLER_STATIC_COMPONENT;
        break;
      case 'menu':
        $name = self::CONTROLLER_STATIC_MENU;
        break;
    }
    return $name;
  }
  
}
