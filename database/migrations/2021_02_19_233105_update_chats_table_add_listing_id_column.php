<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateChatsTableAddListingIdColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('chats', function (Blueprint $table) {
            $table->unsignedBigInteger('listing_id')->after('reservation_id');
            $table->unsignedBigInteger('creator_id')->after('owner_id');

            $table->foreign('listing_id')->references('id')->on('listings')->onDelete('cascade');
            $table->foreign('creator_id')->references('id')->on('users')->onDelete('cascade');

            $table->dropForeign('chats_reservation_id_foreign');
            $table->dropColumn('reservation_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('chats', function (Blueprint $table) {
            $table->unsignedBigInteger('reservation_id')->after('owner_id');

            $table->foreign('reservation_id')->references('id')->on('reservations')->onDelete('cascade');

            $table->dropForeign('chats_listing_id_foreign');
            $table->dropColumn('listing_id');

            $table->dropForeign('chats_creator_id_foreign');
            $table->dropColumn('creator_id');
        });
    }
}
