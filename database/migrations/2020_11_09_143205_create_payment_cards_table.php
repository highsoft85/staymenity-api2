<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentCardsTable extends Migration
{
    use \App\Services\Database\Migration\MigrationFieldTrait;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_cards', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->string('stripe_token_id');
            $table->string('stripe_card_id');
            $table->string('brand');
            $table->string('last');
            $table->boolean('is_main')->unsigned()->default(0);
            $this->status($table);
            $table->timestamps();

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
        Schema::table('payment_cards', function (Blueprint $table) {
            $table->dropForeign('payment_cards_user_id_foreign');
        });
        Schema::dropIfExists('payment_cards');
    }
}
