<?php


use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class Core extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
      /**
       * Пользователи
       */
      $tableUser = $this->table('core_user', ['comment' => 'Пользователи']);
      $tableUser->addColumn('u_login', 'string', ['null' => false, 'comment' => 'Логин'])
              ->addColumn('u_password', 'string', ['null' => false, 'comment' => 'Хэш пароля'])
              ->addColumn('user_group_id', 'integer', ['null' => false, 'signed' => false, 
                  'comment' => 'Идентификатор группы пользователей'])
              ->addColumn('name_f', 'string', ['null' => true, 'limit' => 50, 'comment' => 'Имя'])
              ->addColumn('name_m', 'string', ['null' => true, 'limit' => 50, 
                  'comment' => 'Отчество'])
              ->addColumn('name_l', 'string', ['null' => true, 'limit' => 50, 
                  'comment' => 'Фамилия'])
              ->addColumn('email', 'string', ['null' => false, 'comment' => 'Электронная почта'])
              ->addColumn('email_verification_code', 'string', ['null' => false, 
                  'comment' => 'Код для потврерждения электронной почты'])
              ->addColumn('email_verified', 'boolean', ['null' => false, 'default' => 0, 
                  'comment' => 'Флаг, определяющий верифицирована почта или нет'])
              ->addColumn('registration_date', 'timestamp', ['null' => true, 'default' => null, 
                  'comment' => 'Дата регистрации'])
              ->addColumn('avatar', 'string', ['null' => true, 
                  'comment' => 'Имя файла с изображением на заставку'])
              ->addColumn('phone', 'string', ['null' => true, 'limit' => 50, 
                  'comment' => 'Номер телефона'])
              ->create();
      /**
       * Группы пользователей
       */
      $tableUserGroup = $this->table('core_user_group', ['comment' => 'Группы пользователей']);
      $tableUserGroup->addColumn('parent_id', 'integer', ['null' => false, 'default' => 0, 
                  'signed' => false, 'comment' => 'Идентификатор родительской группы'])
              ->addColumn('name', 'string', ['null' => false, 'limit' => 50, 
                  'comment' => 'Название группы'])
              ->addColumn('description', 'string', ['null' => true, 'comment' => 'Описание'])
              ->create();
      /**
       * Права доступа к сущностям
       */
      $tableUserGroupAccess = $this->table('core_user_group_access', 
              ['comment' => 'Таблица с правами доступа к сущностям']);
      $tableUserGroupAccess->addColumn('user_group_id', 'integer', ['null' => false, 
                  'signed' => false, 'comment' => 'Идентификатор группы пользователей'])
              ->addColumn('object_name', 'string', ['null' => false, 'limit' => 50, 
                  'comment' => 'Название объекта (сущности)'])
              ->addColumn('object_id', 'integer', ['null' => true, 'signed' => false, 
                  'comment' => 'Идентификатор объекта, если указан, то значит правило '
                  . 'распространяется на определенную запись заданной сущности, если не задан, то '
                  . 'на все записи сущности'])
              ->addColumn('a_admin', 'boolean', ['null' => false, 'default' => 0,
                  'comment' => 'Предоставлены права администратора'])
              ->addColumn('a_read', 'boolean', ['null' => false, 'default' => 0,
                  'comment' => 'Предоставлены права на чтение'])
              ->addColumn('a_write', 'boolean', ['null' => false, 'default' => 0,
                  'comment' => 'Предоставлены права на запись'])
              ->create();
      /**
       * Страницы
       */
      $tablePage = $this->table('core_page', ['comment' => 'Таблица с содержимым страниц']);
      $tablePage->addColumn('page_section_id', 'integer', ['null' => false, 'signed' => false,
                  'comment' => 'Идентификатор раздела страниц'])
              ->addColumn('alias', 'string', ['null' => false, 
                  'comment' => 'Алиас страницы, указывается на латинице, работает в url'])
              ->addColumn('link', 'string', ['null' => true, 'default' => null, 
                  'comment' => 'Если указан, то будет идти подгрузка содержимого страницы из поля'])
              ->addColumn('title', 'string', ['null' => false, 
                  'comment' => 'Название страницы, идет в заголовок браузера'])
              ->addColumn('name', 'string', ['null' => false, 
                  'comment' => 'Название страницы, идет в заголовок содержимого страницы'])
              ->addColumn('teaser', 'string', ['null' => true, 
                  'comment' => 'Короткий предваряющий текст содержимого страницы'])
              ->addColumn('text', 'text', ['null' => true,
                  'comment' => 'Полный текст содержимого страницы'])
              ->addColumn('date_create', 'timestamp', ['null' => true, 'default' => null,
                  'comment' => 'Дата создания страницы'])
              ->addColumn('date_public', 'timestamp', ['null' => true, 'default' => null,
                  'comment' => 'Дата публикации страницы'])
              ->addColumn('date_update', 'timestamp', ['null' => true, 'default' => null,
                  'comment' => 'Дата обновления содержимого страницы'])
              ->addColumn('published', 'boolean', ['null' => false, 'signed' => false, 
                  'default' => 0, 
                  'comment' => 'Флаг, определяющий размещение или снятие с публикации материала'])
              ->create();
      /**
       * Разделы страниц
       */
      $tablePageSection = $this->table('core_page_section', 
              ['comment' => 'Таблица с разделами страниц']);
      $tablePageSection->addColumn('name', 'string', ['null' => false, 
                  'comment' => 'Название группы'])
              ->addColumn('description', 'string', ['null' => true, 'comment' => 'Описание'])
              ->create();
      /**
       * Меню
       */
      $tableMenu = $this->table('core_menu', ['comment' => 'Таблица со списком меню сайта']);
      $tableMenu->addColumn('name', 'string', ['null' => false, 
                  'comment' => 'Название меню, латиница'])
              ->addColumn('caption', 'string', ['null' => true, 'comment' => 'Заголовок меню'])
              ->addColumn('type', 'string', ['null' => true, 'limit' => 20, 
                  'comment' => 'Тип меню для формирования вида, связан с bootstrap компонентами'])
              ->addColumn('template', 'string', ['null' => true, 
                  'comment' => 'Имя файла шаблона для вывода меню'])
              ->addColumn('description', 'string', ['null' => true, 'comment' => 'Примечание'])
              ->create();
      /**
       * Пункты меню
       */
      $tableMenuItem = $this->table('core_menu_item', ['comment' => 'Таблица с пунктами меню']);
      $tableMenuItem->addColumn('parent_id', 'integer', ['null' => false, 'signed' => false, 
                  'default' => 0, 'comment' => 'Идентификатор родительского пункта меню'])
              ->addColumn('menu_id', 'integer', ['null' => false, 'signed' => false, 
                  'comment' => 'Идентификатор меню'])
              ->addColumn('name', 'string', ['null' => false, 'comment' => 'Текст пункта меню'])
              ->addColumn('link', 'string', ['null' => false, 'comment' => 'Ссылка пункта меню'])
              ->addColumn('weight', 'integer', ['null' => false, 'default' => 100, 
                  'limit' => MysqlAdapter::INT_TINY, 
                  'comment' => 'Вес пункта меню, чем меньше, тем выше в списке'])
              ->addColumn('enabled', 'boolean', ['null' => false, 'default' => 0,
                  'comment' => 'Определяет включен пункт меню или нет'])
              ->create();
    }
}
