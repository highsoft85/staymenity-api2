<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Администратор
        $oAdmin = Role::where('name', User::ROLE_ADMIN)->first();
        if (is_null($oAdmin)) {
            factory(Role::class)->create([
                'name' => User::ROLE_ADMIN,
                'title' => 'Admin',
            ]);
        }

        // Пользователь
        $oManager = Role::where('name', User::ROLE_MANAGER)->first();
        if (is_null($oManager)) {
            factory(Role::class)->create([
                'name' => User::ROLE_MANAGER,
                'title' => 'Manager',
            ]);
        }

        // Пользователь
        $oHost = Role::where('name', User::ROLE_HOST)->first();
        if (is_null($oHost)) {
            factory(Role::class)->create([
                'name' => User::ROLE_HOST,
                'title' => 'Host',
            ]);
        }

        // Владелец
        $oOwner = Role::where('name', User::ROLE_OWNER)->first();
        if (is_null($oOwner)) {
            factory(Role::class)->create([
                'name' => User::ROLE_OWNER,
                'title' => 'Owner',
            ]);
        }

        // Пользователь
        $oGuest = Role::where('name', User::ROLE_GUEST)->first();
        if (is_null($oGuest)) {
            factory(Role::class)->create([
                'name' => User::ROLE_GUEST,
                'title' => 'Guest',
            ]);
        }
    }
}
