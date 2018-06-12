<?php


use Phinx\Seed\AbstractSeed;

class UserGroupSeeder extends AbstractSeed
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
          'parent_id' => 0,
          'name' => 'Зарегистрированные пользователи',
          'description' => '',
        ], 
        [
          'id' => 2,
          'parent_id' => 1,
          'name' => 'Администраторы',
          'description' => '',
        ], 
        [
          'id' => 3,
          'parent_id' => 0,
          'name' => 'Гости',
          'description' => '',
        ],   
      ];
      $menu = $this->table('core_user_group');
      $menu->insert($data)->save();
    }
}
