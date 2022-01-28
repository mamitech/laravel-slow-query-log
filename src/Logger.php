<?php
namespace Vynhart\SlowQueryLog;

use Illuminate\Database\Events\QueryExecuted;

class Logger
{
    public function log(QueryExecuted $query)
    {
        $fname = 'slow-query-' . date('Y-m-d');
        $file = fopen(storage_path('logs/' . $fname), 'a');
        fwrite($file, $query->sql . "\n");
    }
}