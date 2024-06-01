<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHostfullyUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hostfully_users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('lead_uid');
            $table->unsignedBigInteger('user_id');
            $table->longText('external')->nullable()->default(null);
            $table->timestamp('last_sync_at')->nullable()->default(null);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hostfully_users', function (Blueprint $table) {
            $table->dropForeign('hostfully_users_user_id_foreign');
        });
        Schema::dropIfExists('hostfully_users');
    }
}
