<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatePaymentCardsTableAddStripePaymentMethodIdColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment_cards', function (Blueprint $table) {
            $table->string('stripe_payment_method_id')->after('user_id');
            $table->dropColumn('stripe_token_id');
            $table->dropColumn('stripe_card_id');
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
            $table->dropColumn('stripe_payment_method_id');
            $table->string('stripe_token_id')->after('user_id');
            $table->string('stripe_card_id')->after('stripe_token_id');
        });
    }
}
