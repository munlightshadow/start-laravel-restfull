<?php

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$role_employee = new Role();
		$role_employee->name = 'admin';
		$role_employee->description = 'Admin user';
		$role_employee->save();

		$role_manager = new Role();
		$role_manager->name = 'owner';
		$role_manager->description = 'Owner wine shop';
		$role_manager->save();

		$role_manager = new Role();
		$role_manager->name = 'user';
		$role_manager->description = 'User wine shop';
		$role_manager->save();		
    }
}
