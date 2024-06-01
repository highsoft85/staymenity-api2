<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImagesTable extends Migration
{
    use \App\Services\Database\Migration\MigrationFieldTrait;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('images', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('type')->nullable()->default(null);
            $table->morphs('imageable');
            $table->text('options')->nullable()->default(null);
            $table->string('filename')->nullable()->default(null);
            $table->text('info')->nullable()->default(null);
            $table->string('source')->nullable()->default(null);
            $table->boolean('is_main')->unsigned()->default(0);
            $table->tinyInteger('number')->unsigned()->nullable()->default(null);
            $this->priority($table);
            $this->status($table);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('images');
    }
}
