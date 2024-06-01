<?php

declare(strict_types=1);

namespace App\Console\Commands\User;

use App\Console\Commands\Common\CommandTrait;
use App\Models\User;
use App\Services\Model\UserServiceModel;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class UserCreateCommand extends Command
{
    use CommandTrait;

    /**
     *
     */
    const SIGNATURE = 'user:create';

    /**
     * The name and signature of the console command.
     *
     * php artisan user:create
     * php artisan user:create --testing
     * php artisan user:create --role=admin --email=admin@ag.digital --password=1234567890
     *
     * @var string
     */
    protected $signature = self::SIGNATURE . '
        {--role= : role пользователя}
        {--email= : email пользователя}
        {--password= : password пользователя}
        {--testing : включить режим тестирования}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Добавить пользователя';

    /**
     * @var bool
     */
    private $log = false;

    /**
     * @var string|null
     */
    private $email = null;

    /**
     * @var string|null
     */
    private $role = null;

    /**
     * @var string|null
     */
    private $password = null;

    /**
     * CheckQueuesCommand constructor.
     */
    public function __construct()
    {
        @parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @throws \Throwable
     */
    public function handle(): void
    {
        $this->start();
        /** @var User $oUser */
        $oUser = User::create([
            'first_name' => 'User',
            'login' => $this->email,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);
        (new UserServiceModel($oUser))->afterCreate();
        if (!is_null($this->role) && in_array($this->role, User::rolesList())) {
            $oUser->assignRole($this->role);
        }
        $this->successLog('User was created.');

        $this->finish();
    }
}
