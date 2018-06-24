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
use smalex86\webframework\core\FunctionList;

/**
 * User info view
 *
 * @author Alexandr Smirnov <mail_er@mail.ru>
 */
class Info extends View {
  
  /**
   * Шаблон текста страницы для формы входа
   * @var string
   */
  protected $tplUserLoginLinkAndForm = <<<'LOGIN'
<div class="navbar-form navbar-right">
  <button type="button" data="userLogin" class="btn btn-success" data-toggle="modal" data-target="#modalLoginForm">Вход</button>
  <a href="%s?page=user&action=registration" class="btn btn-link">Регистрация</a>
</div>
<div class="modal fade" id="modalLoginForm">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Вход</h4>
      </div>
      <form role='form' method='post'>
        <div class="modal-body">
          <div class='form-group'>
            <input type='text' placeholder='Логин' class='form-control' name='user[ulogin]'>
          </div>
          <div class='form-group'>
            <input type='password' placeholder='Пароль' class='form-control' name='user[upassword]'>
          </div>
        </div>
        <div class="modal-footer">
          <input type="hidden" name="area" value="component">
          <input type="submit" type="button" class="btn btn-primary" name="user[submitLogin]" class="btn btn-success" value="Вход">
          <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
	</div>
      </form>
    </div>
  </div>
</div>
LOGIN;
  
  /**
   * Шаблон компонента для вывода короткой инфы о пользователе
   * @var string
   */
  protected $tplUserInfo = <<<'INFO'
<form class="navbar-form navbar-right" role="form" method="post" action="%s">
  <input type="submit" name="user[submitExit]" value="Выход" class="btn btn-success">
</form>
<p class='navbar-text navbar-right'>
  <span class='glyphicon glyphicon-user'></span>
  <a href='%s?page=user&profile=info' class='navbar-link'>%s</a>
</p>
INFO;
  
  
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
      if (empty($data)) {
        $result = sprintf($this->tplUserLoginLinkAndForm, FunctionList::getScriptName());
      } else {
        $result = sprintf($this->tplUserInfo, FunctionList::getScriptName(), 
                FunctionList::getScriptName(), $data['name']);
      }
    } catch (\Exception $ex) {
      $msg = 'Build body error: ' . $ex->getMessage();
      $this->logger->error($msg);
      throw new ViewException($msg);
    }
    return $result;
  }

}
