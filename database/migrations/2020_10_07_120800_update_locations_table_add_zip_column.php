<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateLocationsTableAddZipColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('locations', function (Blueprint $table) {
            //$table->string('city')->nullable()->default(null)->after('address');
            $table->string('province_code')->nullable()->default(null)->after('province');
            $table->string('zip', 50)->nullable()->default(null)->after('province_code');
            $table->dropColumn('index');
            //$table->dropColumn('locality');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('locations', function (Blueprint $table) {
            //$table->string('locality')->nullable()->default(null)->after('country_code');
            $table->string('index', 50)->nullable()->default(null)->after('country_code');
            $table->dropColumn('zip');
            $table->dropColumn('province_code');
            //$table->dropColumn('city');
        });
    }
}
