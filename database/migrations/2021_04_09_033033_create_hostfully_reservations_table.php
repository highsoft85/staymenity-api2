<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHostfullyReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hostfully_reservations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uid');
            $table->unsignedBigInteger('reservation_id')->nullable()->default(null);
            $table->longText('external')->nullable()->default(null);
            $table->timestamp('last_sync_at')->nullable()->default(null);
            $table->timestamps();

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
        Schema::table('hostfully_reservations', function (Blueprint $table) {
            $table->dropForeign('hostfully_reservations_reservation_id_foreign');
        });
        Schema::dropIfExists('hostfully_reservations');
    }
}
