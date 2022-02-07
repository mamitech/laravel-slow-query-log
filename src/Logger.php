<?php

namespace Vynhart\SlowQueryLog;

use Carbon\Carbon;
use Illuminate\Database\Events\QueryExecuted;
use Vynhart\SlowQueryLog\Models\SlowQuery;

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

        if ($this->isLogToDb()) {
            return $this->logToDb($data);
        }

        $this->logToFile($data);
    }

    private function isBelowThreshold($time)
    {
        return $time < app()->config['slow-query-log.min-threshold'];
    }

    private function isLogToDb()
    {
        return app()->config['slow-query-log.storage'] === 'database';
    }

    private function logToDb($data)
    {
        $slowQ = new SlowQuery;
        $slowQ->time = $data['time'];
        $slowQ->sql = $data['sql'];
        $slowQ->path = $data['path'];
        $slowQ->traces = json_encode($data['traces']);
        $slowQ->created_at = Carbon::now();

        $slowQ->save();
    }

    private function logToFile($data)
    {
        $logFile = new LogFile;
        $logFile->append(json_encode($data) . self::Separator);
    }

    private function getCallTraces()
    {
        $config = app()->config;
        return array_filter(debug_backtrace(), function ($trace) use ($config) {
            if (empty($trace['file'])) {
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
