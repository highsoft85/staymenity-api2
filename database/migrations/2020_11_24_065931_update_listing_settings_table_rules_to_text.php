<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateListingSettingsTableRulesToText extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('listing_settings', function (Blueprint $table) {
            $table->text('amenities')->nullable()->default(null)->change();
            $table->text('rules')->nullable()->default(null)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('listing_settings', function (Blueprint $table) {
            $table->string('amenities')->nullable()->default(null)->change();
            $table->string('rules')->nullable()->default(null)->change();
        });
    }
}
