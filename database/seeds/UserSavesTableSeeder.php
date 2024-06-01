<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserSavesTableSeeder extends Seeder
{
    /**
     * @var string[][]
     */
    private $data = [
        [
            'title' => 'LA, swimming pools',
        ],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->data as $data) {
            /** @var User|null $oUser */
            $oUser = \App\Models\User::first();
            if (!is_null($oUser)) {
                factory(\App\Models\UserSave::class)->create(array_merge($data, [
                    'user_id' => $oUser->id,
                ]));
            }
        }
    }
}
