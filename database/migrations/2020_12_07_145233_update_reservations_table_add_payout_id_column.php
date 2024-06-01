<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateReservationsTableAddPayoutIdColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->unsignedBigInteger('transfer_id')->nullable()->default(null)->after('payment_id');
            $table->unsignedBigInteger('payout_id')->nullable()->default(null)->after('transfer_id');
            $table->timestamp('transfer_at')->nullable()->default(null)->after('passed_at');
            $table->timestamp('payout_at')->nullable()->default(null)->after('transfer_at');

            $table->foreign('transfer_id')->references('id')->on('transfers')->onDelete('cascade');
            $table->foreign('payout_id')->references('id')->on('payouts')->onDelete('cascade');
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
            $table->dropForeign('reservations_transfer_id_foreign');
            $table->dropForeign('reservations_payout_id_foreign');

            $table->dropColumn('transfer_id');
            $table->dropColumn('payout_id');

            $table->dropColumn('transfer_at');
            $table->dropColumn('payout_at');
        });
    }
}
