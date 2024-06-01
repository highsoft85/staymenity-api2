<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOptionParametersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('option_parameters', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('option_id')->unsigned();
            $table->string('quantity');
            $table->integer('priority')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });
        Schema::table('option_parameters', function ($table) {
            $table->foreign('option_id')->references('id')->on('options')->onDelete('cascade');
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
        Schema::drop('option_parameters');
        Schema::enableForeignKeyConstraints();
    }
}
