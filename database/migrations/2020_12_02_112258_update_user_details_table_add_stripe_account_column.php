<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUserDetailsTableAddStripeAccountColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_details', function (Blueprint $table) {
            $table->string('test_stripe_account')->nullable()->default(null)->after('customer_id');
            $table->string('stripe_account')->nullable()->default(null)->after('test_stripe_account');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_details', function (Blueprint $table) {
            $table->dropColumn('test_stripe_account');
            $table->dropColumn('stripe_account');
        });
    }
}
