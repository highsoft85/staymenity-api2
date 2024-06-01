<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRulesTable extends Migration
{
    use \App\Services\Database\Migration\MigrationFieldTrait;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rules', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('type');
            $table->string('name');
            $table->string('title');
            $table->string('icon')->nullable()->default(null);
            $this->priority($table);
            $this->status($table);
            $table->timestamps();
        });

        Schema::create('listing_rule', function (Blueprint $table) {
            $table->unsignedBigInteger('listing_id');
            $table->unsignedBigInteger('rule_id');

            $table->foreign('listing_id')->references('id')->on('listings')->onDelete('cascade');
            $table->foreign('rule_id')->references('id')->on('rules')->onDelete('cascade');

            $table->primary(['listing_id', 'rule_id'], 'listing_rule_listing_id_rule_id_primary');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('listing_rule');
        Schema::enableForeignKeyConstraints();

        Schema::dropIfExists('rules');
    }
}
