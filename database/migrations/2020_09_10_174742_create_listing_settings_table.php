<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateListingSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('listing_settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('listing_id');
            $table->string('amenities')->nullable()->default(null);
            $table->string('rules')->nullable()->default(null);
            $table->string('type')->nullable()->default(null);
            $table->boolean('is_dedicated')->unsigned()->nullable()->default(null);
            $table->boolean('is_company')->unsigned()->nullable()->default(null);
            $table->boolean('is_rented_before')->unsigned()->nullable()->default(null);
            $table->text('cancellation_description')->nullable()->default(null);
            $table->tinyInteger('people_max')->unsigned()->nullable()->default(null);
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
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('listing_settings');
        Schema::enableForeignKeyConstraints();
    }
}
