<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHostfullyListingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hostfully_listings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uid');
            $table->unsignedBigInteger('listing_id');
            $table->longText('external')->nullable()->default(null);
            $table->timestamp('last_sync_at')->nullable()->default(null);
            $table->timestamps();

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
        Schema::table('hostfully_listings', function (Blueprint $table) {
            $table->dropForeign('hostfully_listings_listing_id_foreign');
        });
        Schema::dropIfExists('hostfully_listings');
    }
}
