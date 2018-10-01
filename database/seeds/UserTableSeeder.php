<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
	    $role_admin = Role::where('name', 'admin')->first();
	    $role_owner  = Role::where('name', 'owner')->first();
	    $role_user  = Role::where('name', 'user')->first();
	 
	    $employee = new User();
	    $employee->name = 'Admin';
	    $employee->email = 'admin@no-spam.ws';
	    $employee->password = bcrypt('123456');
	    $employee->save();
	    $employee->roles()->attach($role_admin);
	 
	    $employee = new User();
	    $employee->name = 'Owner1';
	    $employee->email = 'owner1@no-spam.ws';
	    $employee->password = bcrypt('123456');
	    $employee->save();
	    $employee->roles()->attach($role_owner);

	    $employee = new User();
	    $employee->name = 'Owner2';
	    $employee->email = 'owner2@no-spam.ws';
	    $employee->password = bcrypt('123456');
	    $employee->save();
	    $employee->roles()->attach($role_owner);

	    $employee = new User();
	    $employee->name = 'User1';
	    $employee->email = 'user1@no-spam.ws';
	    $employee->password = bcrypt('123456');
	    $employee->save();
	    $employee->roles()->attach($role_user);

	    $employee = new User();
	    $employee->name = 'User2';
	    $employee->email = 'user2@no-spam.ws';
	    $employee->password = bcrypt('123456');
	    $employee->save();
	    $employee->roles()->attach($role_user);	    	    	    

    }
}
