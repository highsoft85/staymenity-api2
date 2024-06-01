<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUserCalendarsTableReservationIdColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_calendars', function (Blueprint $table) {
            $table->unsignedBigInteger('reservation_id')->nullable()->default(null)->after('listing_id');

            $table->foreign('reservation_id')->references('id')->on('reservations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_calendars', function (Blueprint $table) {
            $table->dropForeign('user_calendars_reservation_id_foreign');
        });
        Schema::table('user_calendars', function (Blueprint $table) {
            $table->dropColumn('reservation_id');
        });
    }
}
