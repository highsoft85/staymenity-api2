<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateHostfullyWebhookResponsesAddStatusColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hostfully_webhook_responses', function (Blueprint $table) {
            $table->tinyInteger('status')->unsigned()->default(0)->after('property_uid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hostfully_webhook_responses', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}
