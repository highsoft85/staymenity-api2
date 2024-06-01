<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUserCalendarsTableHostfullyReservationIdColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_calendars', function (Blueprint $table) {
            $table->unsignedBigInteger('hostfully_reservation_id')->nullable()->default(null)->after('reservation_id');

            $table->foreign('hostfully_reservation_id')->references('id')->on('hostfully_reservations')->onDelete('cascade');
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
            $table->dropForeign('user_calendars_hostfully_reservation_id_foreign');
        });
        Schema::table('user_calendars', function (Blueprint $table) {
            $table->dropColumn('hostfully_reservation_id');
        });
    }
}
