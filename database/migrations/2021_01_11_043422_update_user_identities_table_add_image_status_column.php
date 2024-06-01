<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUserIdentitiesTableAddImageStatusColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_identities', function (Blueprint $table) {
            $table->text('image_front_response')->nullable()->default(null)->after('errors');
            $table->tinyInteger('image_front_status')->unsigned()->default(0)->after('image_front_response');

            $table->text('image_back_response')->nullable()->default(null)->after('image_front_status');
            $table->tinyInteger('image_back_status')->unsigned()->default(0)->after('image_back_response');

            $table->text('image_selfie_response')->nullable()->default(null)->after('image_back_status');
            $table->tinyInteger('image_selfie_status')->unsigned()->default(0)->after('image_selfie_response');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_identities', function (Blueprint $table) {
            $table->dropColumn('image_front_response');
            $table->dropColumn('image_front_status');

            $table->dropColumn('image_back_response');
            $table->dropColumn('image_back_status');

            $table->dropColumn('image_selfie_response');
            $table->dropColumn('image_selfie_status');
        });
    }
}
