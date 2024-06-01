<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOptionsTable extends Migration
{
    use \App\Services\Database\Migration\MigrationFieldTrait;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('options', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('purpose')->default(\App\Models\Option::PURPOSE_DEFAULT);
            $table->string('name');
            $table->string('title');
            $table->string('description')->nullable()->default(null);
            $table->string('placeholder')->nullable()->default(null);
            $table->string('unit')->nullable()->default(null);
            $table->integer('type')->default(\App\Models\Option::TYPE_TEXT);
            $table->string('tooltip')->nullable()->default(null);
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
        Schema::dropIfExists('options');
    }
}
