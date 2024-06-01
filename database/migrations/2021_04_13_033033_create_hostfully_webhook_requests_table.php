<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHostfullyWebhookRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hostfully_webhook_requests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uid');
            $table->uuid('agency_uid');
            $table->uuid('object_uid');
            $table->string('type')->nullable()->default(null);
            $table->string('event_type');
            $table->string('callback_url')->nullable()->default(null);
            $table->longText('external')->nullable()->default(null);
            $table->timestamp('last_sync_at')->nullable()->default(null);
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
        Schema::dropIfExists('hostfully_webhook_requests');
    }
}
