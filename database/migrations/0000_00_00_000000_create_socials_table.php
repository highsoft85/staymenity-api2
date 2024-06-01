<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSocialsTable extends Migration
{
    use \App\Services\Database\Migration\MigrationFieldTrait;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('socials', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('type')->nullable()->default(null);
            $table->morphs('socialable');
            $table->text('options')->nullable()->default(null);
            $table->string('value')->nullable()->default(null);
            $table->string('description')->nullable()->default(null);
            $table->boolean('is_main')->unsigned()->default(0);
            $this->priority($table);
            $this->status($table);
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
        Schema::dropIfExists('socials');
    }
}
