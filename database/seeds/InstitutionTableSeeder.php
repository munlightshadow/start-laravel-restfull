<?php

use Illuminate\Database\Seeder;
use App\Models\Role;

class InstitutionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Auth::login(\App\Models\User::find(3));
        factory(\App\Models\Institution::class, 1)->create(['user_id' => 2]);

        Auth::login(\App\Models\User::find(3));
        //factory(\App\Models\Institution::class, 3)->create(['user_id' => 3]);
    }
}
