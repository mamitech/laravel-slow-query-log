<?php
namespace Vynhart\SlowQueryLog;

use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\Route;

class Logger
{
    const Separator = '<<!=!>>';

    public function log(QueryExecuted $query)
    {
        if ($this->isBelowThreshold($query->time)) {
            return;
        }

        $data = [
            'time' => $query->time,
            'sql' => $query->sql,
            'path' => \Request::path(),
            'traces' => $this->getCallTraces()
        ];

        $logFile = new LogFile;
        $logFile->append(json_encode($data) . self::Separator);
    }

    private function isBelowThreshold($time)
    {
        $config = app()->config;
        return $time < $config['slow-query-log.min-threshold'];
    }

    private function getCallTraces()
    {
        $config = app()->config;
        return array_filter(debug_backtrace(), function($trace) use ($config) {
            if(empty($trace['file'])) {
                return false;
            }

            $traceIt = true;

            $traceOnly = $config['slow-query-log.trace-only'];
            if (!empty($traceOnly)) {
                $traceIt = str_contains($trace['file'], $traceOnly);
            }

            $traceExclude = $config['slow-query-log.trace-exclude'];
            if ($traceIt && !empty($traceExclude)) {
                $traceIt = !str_contains($trace['file'], $traceExclude);
            }

            return $traceIt;
        });
    }

}