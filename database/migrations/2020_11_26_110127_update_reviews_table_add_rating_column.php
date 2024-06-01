<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateReviewsTableAddRatingColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropForeign('reviews_rating_id_foreign');
            $table->dropColumn('rating_id');

            $table->float('rating', 4, 2)->nullable()->default(null)->after('rating_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->unsignedBigInteger('rating_id')->nullable()->default(null)->after('rating');
            $table->foreign('rating_id')->references('id')->on('ratings')->onDelete('set null');

            $table->dropColumn('rating');
        });
    }
}
