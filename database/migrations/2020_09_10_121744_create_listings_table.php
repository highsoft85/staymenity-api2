<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateListingsTable extends Migration
{
    use \App\Services\Database\Migration\MigrationFieldTrait;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('listings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->string('name');
            $table->string('title');
            $table->unsignedBigInteger('type_id');
            $table->integer('price')->unsigned()->nullable()->default(null);
            $table->integer('deposit')->unsigned()->nullable()->default(null);
            $table->integer('cleaning_fee')->unsigned()->nullable()->default(null);
            $table->text('description')->nullable()->default(null);
            $table->tinyInteger('guests_size')->unsigned()->nullable()->default(null);
            $table->timestamp('banned_at')->nullable()->default(null);
            $this->status($table);
            $table->timestamps();

            $table->foreign('type_id')->references('id')->on('types')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('listings');
        Schema::enableForeignKeyConstraints();
    }
}
