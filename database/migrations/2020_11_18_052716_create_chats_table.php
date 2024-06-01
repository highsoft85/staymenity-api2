<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatsTable extends Migration
{
    use \App\Services\Database\Migration\MigrationFieldTrait;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chats', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('owner_id');
            $table->unsignedBigInteger('reservation_id');
            $table->string('title')->nullable()->default(null);
            $table->timestamp('last_message_at')->nullable()->default(null);
            $this->status($table);
            $table->timestamps();

            $table->foreign('owner_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('reservation_id')->references('id')->on('reservations')->onDelete('cascade');
        });

        Schema::create('user_chat', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('chat_id');

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('chat_id')->references('id')->on('chats')->onDelete('cascade');

            $table->primary(['user_id', 'chat_id'], 'user_chat_user_id_chat_id_primary');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('user_chat');
        Schema::enableForeignKeyConstraints();

        Schema::table('chats', function (Blueprint $table) {
            $table->dropForeign('chats_owner_id_foreign');
            $table->dropForeign('chats_reservation_id_foreign');
        });
        Schema::dropIfExists('chats');
    }
}
