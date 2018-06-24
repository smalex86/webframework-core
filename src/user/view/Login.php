<?php

/*
 * This file is part of the smalex86 package.
 *
 * (c) Alexandr Smirnov <mail_er@mail.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace smalex86\webframework\core\user\view;

use smalex86\webframework\core\View;
use smalex86\webframework\core\exception\ViewException;

/**
 * User login view
 *
 * @author Alexandr Smirnov <mail_er@mail.ru>
 */
class Login extends View {
  
  /* 
   *  TODO: Сделать передачу точки входа в шаблон для формирования ссылки action в форме
   */
  
  /**
   * Шаблон текста страницы
   * @var string
   */
  protected $viewTemplate = <<<'TEXT'
<div class="row">
  <div class="col-xs-12 col-sm-offset-2 col-sm-8 col-md-offset-3 col-md-6 col-lg-offset-4 col-lg-4">
    <form role="form" method="post">
      <div class="panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title">Вход</h3>
        </div> <!-- heading -->
        <div class="panel-body">
          <div class='form-group'>
            <label>Логин</label>
            <input type='text' placeholder='Введите логин' class='form-control' name='user[ulogin]'>
          </div>
          <div class='form-group'>
            <label>Пароль</label>
            <input type='password' placeholder='Введите пароль' class='form-control' name='user[upassword]'>
          </div>
        </div>
        <div class="panel-footer text-right">
          <input type="hidden" name="area" value="component">
          <a href="index.php?page=user&action=registration" class="btn btn-link">Регистрация</a>
          <input type="submit" type="button" class="btn btn-primary" name="user[submitLogin]" class="btn btn-success" value="Вход">
        </div>
      </div>
    </form>
  </div>
</div>
TEXT;
  
  public function getTitle(array $data): string {
    return '';
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
      $result = $this->viewTemplate;
    } catch (\Exception $ex) {
      $msg = 'Build body error: ' . $ex->getMessage();
      $this->logger->error($msg);
      throw new ViewException($msg);
    }
    return $result;
  }

}
