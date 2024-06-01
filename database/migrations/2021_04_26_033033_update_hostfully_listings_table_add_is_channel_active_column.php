<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateHostfullyListingsTableAddIsChannelActiveColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hostfully_listings', function (Blueprint $table) {
            $table->boolean('is_channel_active')->default(0)->after('last_sync_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hostfully_listings', function (Blueprint $table) {
            $table->dropColumn('is_channel_active');
        });
    }
}
