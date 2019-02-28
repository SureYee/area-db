<?php

namespace Sureyee\AreaDB\Drivers;


use Illuminate\Support\Facades\DB;
use Sureyee\AreaDB\Driver;

class LaravelDriver implements Driver
{

    public function get(int $code)
    {
        return DB::connection('sqlite')
            ->table('divisions')
            ->where('id', $code)
            ->first();
    }
}