<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /** @var User $admin */
        $admin = User::create([
            'current_role' => User::ROLE_HOST,
            'first_name' => 'Admin',
            'last_name' => 'Admin',
            'email' => 'admin@admin.com',
            'login' => 'admin@admin.com',
            'password' => Hash::make('1234567890'),
        ]);
        $admin->assignRole(User::ROLE_ADMIN);
        $admin->assignRole(User::ROLE_HOST);
    }
}
