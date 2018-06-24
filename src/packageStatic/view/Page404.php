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

/**
 * Page404
 *
 * @author Alexandr Smirnov <mail_er@mail.ru>
 */
class Page404 extends View {
  
  protected $viewTemplate = <<<'TEXT'
<div class="page-header">
<h1>404 - Страница не найдена</h1>
</div>
<p>Страница не найдена</p>
TEXT;
  
  public function getText() {
    
  }

  public function getTitle(array $data): string {
    return 'Страница не найдена';
  }

  public function getView(array $data): string {
    return $this->viewTemplate;
  }

}
