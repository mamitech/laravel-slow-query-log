<?php

namespace Mamitech\SlowQueryLog;

class LogFile
{
    public function getFilePath()
    {
        $fname = 'slow-query-' . date('Y-m-d');
        return storage_path('logs/' . $fname);
    }

    public function isFileExists()
    {
        return file_exists($this->getFilePath());
    }

    public function append($log)
    {
        fwrite(
            fopen($this->getFilePath(), 'a'),
            $log
        );
    }
}