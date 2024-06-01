<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    use \App\Services\Database\Migration\MigrationFieldTrait;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('email')->unique();
            $table->string('login');
            $table->string('phone')->nullable()->default(null);
            $table->string('first_name')->nullable()->default(null);
            $table->string('last_name')->nullable()->default(null);
            $table->timestamp('email_verified_at')->nullable()->default(null);
            $table->timestamp('phone_verified_at')->nullable()->default(null);
            $table->string('password');
            $table->rememberToken();
            $table->date('birthday_at')->nullable()->default(null);
            $table->boolean('gender')->unsigned()->nullable()->default(null);
            $table->tinyInteger('age')->unsigned()->nullable()->default(null);
            $table->timestamp('banned_at')->nullable()->default(null);
            $this->status($table, \App\Models\User::STATUS_ACTIVE);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
