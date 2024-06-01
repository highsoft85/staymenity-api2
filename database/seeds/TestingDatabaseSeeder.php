<?php

declare(strict_types=1);

use Illuminate\Database\Seeder;

class TestingDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // php artisan migrate:fresh --env=testing
        // php artisan db:seed --class=TestingDatabaseSeeder --env=testing
        $this->call(RolesTableSeeder::class);
        $this->call(TypesTableSeeder::class);
        $this->call(AmenitiesTableSeeder::class);
        $this->call(RulesTableSeeder::class);
        //$this->call(AdminTableSeeder::class);

        //$this->call(UsersTableSeeder::class);
        //$this->call(ListingTableSeeder::class);
        //$this->call(UserSavesTableSeeder::class);
    }
}
