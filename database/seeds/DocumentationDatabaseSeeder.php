<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Database\Seeder;

class DocumentationDatabaseSeeder extends Seeder
{
    use \Tests\FactoryModelTrait;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //php artisan db:seed --class=DocumentationDatabaseSeeder --env=documentation
        $this->call(RolesTableSeeder::class);
        $this->call(TypesTableSeeder::class);
        $this->call(AmenitiesTableSeeder::class);
        $this->call(RulesTableSeeder::class);
        $this->call(AdminTableSeeder::class);

        //$this->call(UsersTableSeeder::class);
        $this->call(ListingOneTableSeeder::class);
        $this->call(UserSavesTableSeeder::class);
        $this->call(UserSavesTableSeeder::class);
        $this->call(OptionsTableSeeder::class);
        $this->call(DocumentationDataSeeder::class);
    }
}
