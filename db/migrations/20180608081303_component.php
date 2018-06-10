<?php


use Phinx\Migration\AbstractMigration;

class Component extends AbstractMigration
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
       * Компоненты
       */
      $tableUser = $this->table('core_component', ['comment' => 'Компоненты']);
      $tableUser->addColumn('name', 'string', ['null' => false, 'comment' => 'Название латиница'])
              ->addColumn('text', 'text', ['null' => true, 'comment' => 'HTML Текст компонента'])
              ->addColumn('filename', 'string', ['null' => true, 
                  'comment' => 'Имя файла с текстом компонента'])
              ->create();
    }
}
