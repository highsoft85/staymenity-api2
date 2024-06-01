<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UsersTableSeeder extends Seeder
{
    /**
     * @return array
     */
    private function data()
    {
        return [
            [
                'email' => 'host@admin.com',
                'login' => 'host@admin.com',
                'current_role' => User::ROLE_HOST,
                'roles' => [
                    User::ROLE_HOST,
                ],
            ], [
                'email' => config('hostfully.user.email'),
                'login' => config('hostfully.user.email'),
                'current_role' => User::ROLE_HOST,
                'roles' => [
                    User::ROLE_HOST,
                ],
            ],
        ];
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->data() as $data) {
            if (empty($data['login'])) {
                continue;
            }
            $oItem = \App\Models\User::where('login', $data['login'])->first();
            if (is_null($oItem)) {
                $roles = [];
                if (isset($data['roles'])) {
                    $roles = $data['roles'];
                    unset($data['roles']);
                }
                /** @var User $oUser */
                $oUser = factory(User::class)->create($data);
                foreach ($roles as $role) {
                    $oUser->assignRole($role);
                }
            }
        }
    }
}
