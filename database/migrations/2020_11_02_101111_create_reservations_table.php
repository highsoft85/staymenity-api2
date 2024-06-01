<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservationsTable extends Migration
{
    use \App\Services\Database\Migration\MigrationFieldTrait;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable()->default(null);
            $table->unsignedBigInteger('listing_id')->nullable()->default(null);
            $table->text('message')->nullable()->default(null);
            $table->tinyInteger('guests_size')->nullable()->default(null);
            $table->float('total_price');
            $table->boolean('is_agree');
            $table->timestamp('start_at')->nullable()->default(null);
            $table->timestamp('finish_at')->nullable()->default(null);
            $table->timestamp('accepted_at')->nullable()->default(null);
            $table->timestamp('cancelled_at')->nullable()->default(null);
            $table->timestamp('declined_at')->nullable()->default(null);
            $this->status($table);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('listing_id')->references('id')->on('listings')->onDelete('cascade');
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
            $table->dropForeign('reservations_user_id_foreign');
            $table->dropForeign('reservations_listing_id_foreign');
        });
        Schema::dropIfExists('reservations');
    }
}
