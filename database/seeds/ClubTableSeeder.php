<?php

use Illuminate\Database\Seeder;
use App\Models\Role;

class ClubTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach(\App\Models\Institution::get() as $institution){
            Auth::login($institution->owner);
            factory(\App\Models\Club::class, 10)->create(['institution_id' => $institution->id]);
        }

    }
}
