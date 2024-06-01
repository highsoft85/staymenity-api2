<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateListingsTableAddPricePerDayColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('listings', function (Blueprint $table) {
            $table->float('price_per_day')->nullable()->default(null)->after('price');

            //\Illuminate\Support\Facades\DB::statement('ALTER TABLE `listings` MODIFY `price` DOUBLE(8,2) NULL DEFAULT NULL;');

            // не пишет размер
            $table->float('price')->nullable()->default(null)->change();
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
            $table->dropColumn('price_per_day');

            $table->integer('price')->unsigned()->nullable()->default(null)->change();
        });
    }
}
