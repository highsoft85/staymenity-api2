<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    use \App\Services\Database\Migration\MigrationFieldTrait;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('user_from_id')->nullable();
            $table->unsignedBigInteger('user_to_id')->nullable();

            $table->string('provider');
            $table->string('provider_payment_id');
            $table->float('amount');
            $table->float('service_fee');
            $this->status($table);
            $table->timestamps();

            $table->foreign('user_from_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('user_to_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign('payments_user_from_id_foreign');
            $table->dropForeign('payments_user_to_id_foreign');
        });
        Schema::dropIfExists('payments');
    }
}
