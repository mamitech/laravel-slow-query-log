<?php
namespace Vynhart\SlowQueryLog;

use Illuminate\Database\Events\QueryExecuted;

class Logger
{
    public function log(QueryExecuted $query)
    {
        if ($query->time < app()->config['slow-query-log.min-threshold']) {
            return;
        }
        $file = fopen($this->getFilePath(), 'a');
        fwrite($file, $query->sql . "\n");
    }

    public function getFilePath()
    {
        $fname = 'slow-query-' . date('Y-m-d');
        return storage_path('logs/' . $fname);
    }
}