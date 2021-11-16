<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

/**
 * Class AbstractService
 * @package App\Services
 */
abstract class AbstractService
{
    /**
     * @return void
     */
    protected function beginTransaction(): void
    {
        DB::beginTransaction();
    }

    /**
     * @return void
     */
    protected function commitTransaction(): void
    {
        DB::commit();
    }

    /**
     * @return void
     */
    protected function rollbackTransaction(): void
    {
        DB::rollBack();
    }
}
