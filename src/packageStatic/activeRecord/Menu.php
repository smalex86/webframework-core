<?php

/*
 * This file is part of the smalex86 package.
 *
 * (c) Alexandr Smirnov <mail_er@mail.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace smalex86\webframework\core\packageStatic\activeRecord;

use smalex86\webframework\core\ActiveRecord;

/**
 * Menu Active Record
 *
 * @author Alexandr Smirnov
 */
class Menu extends ActiveRecord {
  
  public $mid;
  public $name;
  public $alias;
  public $template;
  public $type;
  
  protected $items = array();


  /**
   * Данный статический метод создает экземпляр данного класса с указанными параметрами
   * @param int $menuId
   * @param string $name
   * @param string $template
   * @param string $type
   * @param array $items
   * @return \smalex86\webframework\core\packageStatic\activeRecord\Menu
   */
  static public function newRecord($menuId, $name, $template, $type, $items) {
    $record = new Menu;
    $record->menuId = $menuId;
    $record->name = $name;
    $record->template = $template;
    $record->type = $type;
    $record->items = $items;
    return $record;
  }
  
  private $itemsTree = array(); // массив дерева элементов меню
        
  /**
   * Метод производит сортировку массива в виде дерева + добавляет атрибут childs
   * Сохраняет данные рекурсивным проходом в $itemsTree
   * @param array $items
   * @param int $miid
   * @return int
   */
  private function getTreeItemsMenu($items, $miid = 0) {
    $childs = 0;
    foreach ($items as $item) {
      if ($item['parent_id'] == $miid) {
        $this->itemsTree[] = $item;
        $childs++;
        $this->itemsTree[count($this->itemsTree)-1]['childs'] = $this->getTreeItemsMenu($items, $item['id']);
      }
    }
    return $childs;
  }
  
  /**
   * Выролняет поиск верхнего предка (используется при поиске активного пункта меню)
   * @param array $items массив элементов меню
   * @param int $parent_miid
   * @return int идентификатор предка
   */
  private function getParentItemMenu($items, $parent_miid) {
    $miid = 0;
    foreach ($items as $item) {
      if ($item['id'] == $parent_miid) {   
        $miid = $item['id'];
        if ($item['parent_id']) {
          if (!$miid2 = $this->getParentItemMenu($items, $item['parent_id'])) { $miid = $miid2; }
        }
        break;
      }
    }
    return $miid;
  }

  /**
   * метод для получения идентификаторы активного пункта меню верхнего уровня
   * @param type $items
   * @return type
   */
  private function getActiveItem($items) {
    $miid = 0;
    $active = false;
    foreach ($items as $item) {
      if (($item['link']) && (strpos($_SERVER['REQUEST_URI'], $item['link'], 0))) {
        // в случае если активный пункт меню вложенный, то установить active у корневого пункта
        $active = true;
        if ($item['parent_id']) {
          $miid = $this->getParentItemMenu($items, $item['parent_id']);
        } else {
          $miid = $item['id'];
          break;
        }    
      }
    }
    // если пользователь находиться на главной, то присвоить пункту меню mainpage значение класса active
    if (!$active) {
      foreach ($items as $item) {
        if ($item['link'] == '' || $item['link'] == null) {
          $miid = $item['id'];
        }
      }
    }            
    return $miid;
  }
  
  /**
   * построение пунктов меню
   * @param array $items сортированный список пунктов меню
   * @param int $miidActive идентификатор активного пункта меню
   * @param int $miid идентификатор пункта меню, с которого начинается построение (для рекурсии)
   * @return string
   */
  private function getTreeItemsMenuHTML($items, $miidActive, $miid = 0) {
    if (!$miid) {
      switch ($this->type) {
        case 'nav':
          $data = '<ul class="nav nav-pills nav-stacked">';    
          break;
        case 'navbar':
          $data = '<ul class="nav navbar-nav">';    
          break;
      }
    } else {
      $data = '<ul class="dropdown-menu">';
    }
    foreach ($items as $item) {
      if ($item['parent_id'] == $miid) {
        $data .= sprintf('<li class="%s%s%s">', ($item['id'] == $miidActive) ? ' current active' : '',
          (!$item['childs'] == 0) ? ' dropdown parent' : '', ($item['name'] == '-') ? ' divider': '');
        if ($item['name'] <> '-') {
          $data .= sprintf('<a%s href="%s">%s%s</a>', (!$item['childs'] == 0) ? ' class="dropdown-toggle" data-toggle="dropdown"' : '',
            ($item['link']) ? $item['link'] : 'index.php', $item['name'], (!$item['childs'] == 0) ? '<b class="caret"></b>' : '');
        }
        if ($item['childs'] > 0) {
          $data .= $this->getTreeItemsMenuHTML($items, $miidActive, $item['id']);
        }
        $data .= "</li>";
      }
    }
    $data .= '</ul>';
    return $data;
  }

  // функция формирования меню
  public function getMenu() {
    $data = null;
    if (is_array($this->items)) {
      $this->getTreeItemsMenu($this->items); // сортировка списка ссылок и назначение атрибута childs
      $miidActive = $this->getActiveItem($this->itemsTree); // поиск активной ссылки
      $data = $this->getTreeItemsMenuHTML($this->itemsTree, $miidActive); // построение списка меню
    }
    return $data;
  }
  
}
