<?php

use Illuminate\Database\Seeder;
use App\Models\Role;

class ProductTableSeeder extends Seeder
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
            factory(\App\Models\Product::class, 50)->create(['institution_id' => $institution->id])
            ->map(function($item){
                (new \App\Models\ProductType)->create([
                    'product_id' => $item->id,
                    'tag' => 'CATEGORY',
                    'slug' => 'WINE',
                    'title' => 'wine'
                ]);
                $rand = rand(0,100) > 50 ? ['RED', 'red'] : ['WHITE', 'white'];
                (new \App\Models\ProductType)->create([
                    'product_id' => $item->id,
                    'tag' => 'WINY_TYPE',
                    'slug' => $rand[0],
                    'title' => $rand[1]
                ]);
            });
        }

    }
}
