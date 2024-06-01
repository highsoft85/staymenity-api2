<?php

declare(strict_types=1);

namespace App\Database\Seeds;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent;
use Illuminate\Support\Str;

trait CommonDatabaseSeederTrait
{
    /**
     * @param string $model
     */
    private function before(string $model)
    {
        \Eloquent::unguard();

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $model::truncate();
    }

    /**
     *
     */
    private function after()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    /**
     * @param int $key
     * @param int $total
     * @return int
     */
    private function priority(int $key, int $total): int
    {
        if ($total < 10) {
            $total = $total * 100;
        } else {
            $total = 100;
        }
        if ($key === 0) {
            $key = 0.7;
        }
        return intval($total / $key);
    }

    /**
     *
     */
    private function clear()
    {
        Schema::disableForeignKeyConstraints();
        foreach (DB::select('SHOW TABLES') as $k => $v) {
            $table = array_values((array)$v)[0];
            if ($table === 'migrations') {
                continue;
            }
            DB::statement('TRUNCATE TABLE `' . $table . '`');
        }
        Schema::enableForeignKeyConstraints();
    }
}
