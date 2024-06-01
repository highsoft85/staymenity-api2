<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSystemOptionValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_option_values', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('option_id')->unsigned();
            $table->bigInteger('parameter_id')->unsigned()->nullable()->default(null);
            $table->longText('value')->nullable()->default(null);
            $table->integer('priority')->default(0);
            $table->timestamps();
        });
        Schema::table('system_option_values', function ($table) {
            $table->foreign('option_id')->references('id')->on('options')->onDelete('cascade');
        });
        Schema::table('system_option_values', function ($table) {
            $table->foreign('parameter_id')->references('id')->on('option_parameters')->onDelete('set null');
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
        Schema::drop('system_option_values');
        Schema::enableForeignKeyConstraints();
    }
}
