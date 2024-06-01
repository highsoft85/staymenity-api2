<?php

declare(strict_types=1);

namespace App\Services\Database\Migration;

use Illuminate\Database\Schema\Blueprint;

trait MigrationFieldTrait
{
    /**
     * @param Blueprint $table
     * @return \Illuminate\Database\Schema\ColumnDefinition
     */
    protected function priority(Blueprint $table)
    {
        return $table->integer('priority')->default(0);
    }

    /**
     * @param Blueprint $table
     * @param int $default
     * @return \Illuminate\Database\Schema\ColumnDefinition
     */
    protected function status(Blueprint $table, int $default = 1)
    {
        return $table->tinyInteger('status')->unsigned()->default($default);
    }

    /**
     * @param Blueprint $table
     * @return \Illuminate\Database\Schema\ColumnDefinition
     */
    protected function age(Blueprint $table)
    {
        return $table->tinyInteger('age')->unsigned()->nullable()->default(null);
    }
}
