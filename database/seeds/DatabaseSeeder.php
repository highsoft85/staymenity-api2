<?php

declare(strict_types=1);

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
        $this->call(RolesTableSeeder::class);
        $this->call(TypesTableSeeder::class);
        //$this->call(AmenitiesTableSeeder::class);
        $this->call(AdminTableSeeder::class);
        //$this->call(UsersTableSeeder::class);
        //$this->call(UsersTableSeeder::class);
        //$this->call(OptionAmenitiesTableSeeder::class);
        //$this->call(OptionRulesTableSeeder::class);
        //$this->call(TermAmenityTableSeeder::class);
        //$this->call(TermRulesTableSeeder::class);

        $this->call(AmenitiesTableSeeder::class);
        $this->call(RulesTableSeeder::class);
        $this->call(FaqsTableSeeder::class);
        $this->call(OptionsTableSeeder::class);

        //$this->call(ListingOneTableSeeder::class);
    }
}
