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

use smalex86\webframework\core\Database;
use Psr\Log\LoggerInterface;

/**
 * Данный класс предназначен для поиска названий классов controller в базе данных
 *
 * @author Alexandr Smirnov
 */
class ControllerFinder {
  
  protected $database = null;
  /**
   * Logger object
   * @var LoggerInterface
   */
  protected $logger = null;
  
  public function __construct(LoggerInterface $logger, Database $database) {
    $this->logger = $logger;
    $this->database = $database;
  }
  
  /**
   * Возвращает имя класса из таблицы Controller по параметрам тип, алиас и действие
   * @param string $type тип Controller
   * @param string $alias искомый алиас
   * @param string $action действие
   * @return string|null
   */
  protected function getClassByAliasController($type, $alias, $action = 'view') {
    $type = $this->database->getSafetyString($type);
    $alias = $this->database->getSafetyString($alias);
    $action = $this->database->getSafetyString($action);
    $query = sprintf('select class '
            . 'from core_controller c '
            . 'left join core_controller_type ct on ct.id = c.controller_type_id '
            . 'where ct.name = "%s" and c.alias = "%s" and c.action = "%s"',
            $type, $alias, $action);
    $row = $this->database->selectSingleRow($query, __FILE__.':'.__LINE__);
    if ($row) {
      $msg = 'alias='.$alias.', class='.$row['class'];
      $this->logger->debug($msg);
      return $row['class'];
    }
    return null;
  }
  
  /**
   * Возвращает класс Controller страницы по алиасу
   * @param string $alias алиас страницы
   * @return string|null
   */
  public function getPageClass($alias, $action = 'view') {
    return $this->getClassByAliasController('page', $alias, $action);
  }

  /**
   * Возвращает класс Controller компонента по алиасу
   * @param string $alias алиас страницы
   * @return string|null
   */  
  public function getComponentClass($alias, $action = 'view') {
    return $this->getClassByAliasController('component', $alias, $action);
  }
  
  /**
   * Возвращает класс Controller меню по алиасу
   * @param string $alias алиас страницы
   * @return string|null
   */
  public function getMenuClass($alias, $action = 'view') {
    return $this->getClassByAliasController('menu', $alias, $action);
  }
  
}
