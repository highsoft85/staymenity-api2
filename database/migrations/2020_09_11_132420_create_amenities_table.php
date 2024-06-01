<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAmenitiesTable extends Migration
{
    use \App\Services\Database\Migration\MigrationFieldTrait;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('amenities', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('type');
            $table->string('name');
            $table->string('title');
            $table->string('icon')->nullable()->default(null);
            $this->priority($table);
            $this->status($table);
            $table->timestamps();
        });

        Schema::create('listing_amenity', function (Blueprint $table) {
            $table->unsignedBigInteger('listing_id');
            $table->unsignedBigInteger('amenity_id');

            $table->foreign('listing_id')->references('id')->on('listings')->onDelete('cascade');
            $table->foreign('amenity_id')->references('id')->on('amenities')->onDelete('cascade');

            $table->primary(['listing_id', 'amenity_id'], 'listing_amenity_listing_id_amenity_id_primary');
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
        Schema::dropIfExists('listing_amenity');
        Schema::enableForeignKeyConstraints();

        Schema::dropIfExists('amenities');
    }
}
