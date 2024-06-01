<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateListingsTableAddPublishedAtColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('listings', function (Blueprint $table) {
            $table->unsignedBigInteger('owner_id')->nullable()->default(null)->after('user_id');
            $table->timestamp('published_at')->nullable()->default(null)->after('banned_at');


            $table->foreign('owner_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('listings', function (Blueprint $table) {
            $table->dropForeign('listings_owner_id_foreign');
            $table->dropColumn('owner_id');
            $table->dropColumn('published_at');
        });
    }
}
