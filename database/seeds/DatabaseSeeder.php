<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Storage::disk('s3')->deleteDirectory('institution');
        Storage::disk('s3')->deleteDirectory('tasting');
        Storage::disk('s3')->deleteDirectory('club');
        Storage::disk('s3')->deleteDirectory('product');

		$this->call(RoleTableSeeder::class);
		$this->call(UserTableSeeder::class);
        $this->call(InstitutionTableSeeder::class);
        $this->call(ClubTableSeeder::class);
        $this->call(ProductTableSeeder::class);
        $this->call(TastingTableSeeder::class);
        $this->call(ReviewTableSeeder::class);
    }
}
