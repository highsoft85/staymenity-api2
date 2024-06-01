<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentChargesTable extends Migration
{
    use \App\Services\Database\Migration\MigrationFieldTrait;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_charges', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('payment_id');
            $table->string('type');
            $table->float('amount');
            $this->status($table);
            $table->timestamps();

            $table->foreign('payment_id')->references('id')->on('payments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payment_charges', function (Blueprint $table) {
            $table->dropForeign('payment_charges_payment_id_foreign');
        });
        Schema::dropIfExists('payment_charges');
    }
}
