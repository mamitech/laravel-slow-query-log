<?php
namespace Vynhart\SlowQueryLog;

use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\Route;

class Logger
{
    const Separator = '<<!=!>>';
    public function log(QueryExecuted $query)
    {
        $config = app()->config;
        if ($query->time < $config['slow-query-log.min-threshold']) {
            return;
        }
        $file = fopen($this->getFilePath(), 'a');
        $traces = array_filter(debug_backtrace(), function($trace) use ($config) {
            $traceOnly = $config['slow-query-log.trace-only'];
            if(empty($traceOnly)) {
                return true;
            }

            if(empty($trace)) {
                return false;
            }

            return str_contains($trace['file'], $traceOnly);
        });

        $path = \Request::path();

        $data = [
            'time' => $query->time,
            'sql' => $query->sql,
            'path' => $path,
            'traces' => $traces
        ];

        fwrite($file, json_encode($data) . self::Separator);
    }

    public function getFilePath()
    {
        $fname = 'slow-query-' . date('Y-m-d');
        return storage_path('logs/' . $fname);
    }
}