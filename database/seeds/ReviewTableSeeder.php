<?php

use Illuminate\Database\Seeder;
use App\Models\Role;

class ReviewTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach(\App\Models\User::find([4,5]) as $user){
            factory(\App\Models\Review::class, 50)->create(['user_id' => $user->id]);
        }

    }
}
