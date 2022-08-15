<?php

namespace App\Repositories\Traits;

use Illuminate\Support\Facades\DB;

trait Transaction
{
    public function beginTransaction(): void
    {
        DB::beginTransaction();
    }

    public function commitTransaction(): void
    {
        DB::commit();
    }

    public function rollbackTransaction(): void
    {
        DB::rollBack();
    }
}
