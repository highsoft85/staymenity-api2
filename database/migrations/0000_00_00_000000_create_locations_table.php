<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocationsTable extends Migration
{
    use \App\Services\Database\Migration\MigrationFieldTrait;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('type')->nullable()->default(null);
            $table->bigInteger('country_id')->unsigned()->nullable()->default(null);
            $table->morphs('locationable');
            $table->point('point', \App\Models\Location::SRID);
            $table->polygon('area')->nullable()->default(null);
            $table->decimal('latitude', 10, 8)->nullable()->default(null);
            $table->decimal('longitude', 11, 8)->nullable()->default(null);
            $table->tinyInteger('zoom')->unsigned()->default(0);
            $table->string('title')->nullable()->default(null);
            $table->string('text')->nullable()->default(null);
            $table->string('address')->nullable()->default(null);
            $table->string('province')->nullable()->default(null);
            $table->string('locality')->nullable()->default(null);
            $table->string('country')->nullable()->default(null);
            $table->string('country_code')->nullable()->default(null);
            $table->string('index')->nullable()->default(null);
            $table->text('options')->nullable()->default(null);
            $this->status($table);
            $table->timestamps();

            $table->spatialIndex('point');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('locations');
    }
}
