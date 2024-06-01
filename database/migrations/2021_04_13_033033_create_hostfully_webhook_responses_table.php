<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHostfullyWebhookResponsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hostfully_webhook_responses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('agency_uid');
            $table->string('event_type');
            $table->uuid('lead_uid')->nullable()->default(null);
            $table->uuid('property_uid')->nullable()->default(null);
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
        Schema::dropIfExists('hostfully_webhook_responses');
    }
}
