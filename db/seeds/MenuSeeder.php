<?php


use Phinx\Seed\AbstractSeed;

class MenuSeeder extends AbstractSeed
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
          'id' => 1,
          'name' => 'main',
          'caption' => 'Главное меню',
          'type' => 'navbar',
          'template' => null
        ],
        [
          'id' => 2,
          'name' => 'user',
          'caption' => 'Меню пользователя',
          'type' => 'nav',
          'template' => null
        ],
        [
          'id' => 3,
          'name' => 'admin',
          'caption' => 'Меню админпанели',
          'type' => 'nav',
          'template' => null
        ]  
      ];
      $menu = $this->table('core_menu');
      $menu->insert($data)->save();
    }
}
