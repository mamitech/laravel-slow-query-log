<?php

namespace Vynhart\SlowQueryLog;

class FileInstaller
{
    function installLogFile()
    {
        if (!file_exists(storage_path('logs'))) {
            mkdir(storage_path('logs'), 0755, true);
        }

        $logFile = new LogFile;
        if (!$logFile->isFileExists()) {
            touch($logFile->getFilePath());
        }
    }
}
