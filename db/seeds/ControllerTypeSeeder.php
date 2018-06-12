<?php


use Phinx\Seed\AbstractSeed;

class ControllerTypeSeeder extends AbstractSeed
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
          'name' => 'page',
          'description' => ''
        ],
        [
          'name' => 'component',
          'description' => ''
        ],
        [
          'name' => 'menu',
          'description' => ''
        ], 
      ];
      $menu = $this->table('core_controller_type');
      $menu->insert($data)->save();
    }
}
