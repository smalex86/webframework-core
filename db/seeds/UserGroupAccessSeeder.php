<?php


use Phinx\Seed\AbstractSeed;

class UserGroupAccessSeeder extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
      $data = [
        // администраторы
        [
          'id' => 1,
          'user_group_id' => 2,
          'object_name' => 'page',
          'object_id' => null,
          'a_admin' => 1,
          'a_read' => 1,
          'a_write' => 1
        ], 
        [
          'id' => 2,
          'user_group_id' => 2,
          'object_name' => 'page_section',
          'object_id' => null,
          'a_admin' => 1,
          'a_read' => 1,
          'a_write' => 1
        ],
        [
          'id' => 3,
          'user_group_id' => 2,
          'object_name' => 'menu',
          'object_id' => null,
          'a_admin' => 1,
          'a_read' => 1,
          'a_write' => 1
        ],
        [
          'id' => 4,
          'user_group_id' => 2,
          'object_name' => 'menu_item',
          'object_id' => null,
          'a_admin' => 1,
          'a_read' => 1,
          'a_write' => 1
        ],
        // зарегистрированные
        [
          'id' => 5,
          'user_group_id' => 1,
          'object_name' => 'page',
          'object_id' => null,
          'a_admin' => 0,
          'a_read' => 1,
          'a_write' => 1
        ], 
        [
          'id' => 6,
          'user_group_id' => 1,
          'object_name' => 'page_section',
          'object_id' => null,
          'a_admin' => 0,
          'a_read' => 1,
          'a_write' => 0
        ],
        [
          'id' => 7,
          'user_group_id' => 1,
          'object_name' => 'menu',
          'object_id' => null,
          'a_admin' => 0,
          'a_read' => 1,
          'a_write' => 0
        ],
        [
          'id' => 8,
          'user_group_id' => 1,
          'object_name' => 'menu_item',
          'object_id' => null,
          'a_admin' => 0,
          'a_read' => 1,
          'a_write' => 0
        ],
        // гости
        [
          'id' => 9,
          'user_group_id' => 3,
          'object_name' => 'page',
          'object_id' => null,
          'a_admin' => 0,
          'a_read' => 1,
          'a_write' => 0
        ], 
        [
          'id' => 10,
          'user_group_id' => 3,
          'object_name' => 'page_section',
          'object_id' => null,
          'a_admin' => 0,
          'a_read' => 1,
          'a_write' => 0
        ],
        [
          'id' => 11,
          'user_group_id' => 3,
          'object_name' => 'menu',
          'object_id' => null,
          'a_admin' => 0,
          'a_read' => 1,
          'a_write' => 0
        ],
        [
          'id' => 12,
          'user_group_id' => 3,
          'object_name' => 'menu_item',
          'object_id' => null,
          'a_admin' => 0,
          'a_read' => 1,
          'a_write' => 0
        ],
      ];
      $table = $this->table('core_user_group_access');
      $table->insert($data)->save();
    }
}
