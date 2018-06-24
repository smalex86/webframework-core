<?php

/*
 * This file is part of the smalex86 package.
 *
 * (c) Alexandr Smirnov <mail_er@mail.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace smalex86\webframework\core\packageStatic\view;

use smalex86\webframework\core\View;
use smalex86\webframework\core\exception\ViewException;

/**
 * default page view for static Page
 *
 * @author Alexandr Smirnov <mail_er@mail.ru>
 */
class Page extends View {
  
  /**
   * Шаблон текста страницы
   * @var string
   */
  protected $viewTemplate = <<<'TEXT'
<div class="page-header">
<h1>%s</h1>
</div>
%s
TEXT;
  /**
   * Шаблон заголовка страницы
   * @var string
   */
  protected $titleTemplate = '%s';
  
  /**
   * Вернуть заголовок страницы
   * 
   * @param array $data Должен содержать элемент 'title'(string)
   * @return string
   * @throws ViewException
   */
  public function getTitle(array $data): string {
    try {
      $result = sprintf($this->titleTemplate, $data['title']);
    } catch (\Exception $ex) {
      $msg = 'Build title error: ' . $ex->getMessage();
      $this->logger->error($msg);
      throw new ViewException($msg);
    }
    return $result;
  }
  /**
   * Получить текст страницы
   * 
   * @param array $data Должен содержать элемент 'title'(string) и 'body'(string)
   * @return string
   * @throws ViewException
   */
  public function getView(array $data): string {
    try {
      $result = sprintf($this->viewTemplate, $data['title'], $data['body']);
    } catch (\Exception $ex) {
      $msg = 'Build body error: ' . $ex->getMessage();
      $this->logger->error($msg);
      throw new ViewException($msg);
    }
    return $result;
  }

}
