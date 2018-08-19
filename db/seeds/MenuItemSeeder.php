<?php


use Phinx\Seed\AbstractSeed;

class MenuItemSeeder extends AbstractSeed
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
        [
          'parent_id' => 0,
          'menu_id' => 1,
          'name' => 'Главная',
          'link' => '',
          'weight' => 100,
          'enabled' => 1
        ],
        [
          'parent_id' => 0,
          'menu_id' => 1,
          'name' => 'Новости',
          'link' => '<<script>>?page=blog&section=news',
          'weight' => 100,
          'enabled' => 1
        ],
        [
          'parent_id' => 0,
          'menu_id' => 2,
          'name' => 'Личный кабинет',
          'link' => '<<script>>?page=user&section=info',
          'weight' => 100,
          'enabled' => 1
        ],
        [
          'parent_id' => 0,
          'menu_id' => 3,
          'name' => 'Пользователи',
          'link' => '<<script>>?page=usermanager',
          'weight' => 100,
          'enabled' => 1
        ],  
        [
          'parent_id' => 0,
          'menu_id' => 3,
          'name' => 'Материалы',
          'link' => '<<script>>?page=pagemanager',
          'weight' => 100,
          'enabled' => 1
        ],
        [
          'parent_id' => 0,
          'menu_id' => 3,
          'name' => 'Меню',
          'link' => '<<script>>?page=menumanager',
          'weight' => 100,
          'enabled' => 1
        ],
        [
          'parent_id' => 0,
          'menu_id' => 3,
          'name' => 'Файлы',
          'link' => '<<script>>?page=filemanager',
          'weight' => 100,
          'enabled' => 1
        ]
      ];
      $menu = $this->table('core_menu_item');
      $menu->insert($data)->save();
    }
}
