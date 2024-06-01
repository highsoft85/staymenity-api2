<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateReservationsTableAddTimezoneColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->string('timezone')->nullable()->default(null)->after('status');
            $table->timestamp('server_start_at')->nullable()->default(null)->after('is_agree');
            $table->timestamp('server_finish_at')->nullable()->default(null)->after('server_start_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropColumn('timezone');
            $table->dropColumn('server_start_at');
            $table->dropColumn('server_finish_at');
        });
    }
}
