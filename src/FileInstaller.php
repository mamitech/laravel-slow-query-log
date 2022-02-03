<?php

namespace Vynhart\SlowQueryLog;

class FileInstaller
{
    function __construct($app)
    {
        $this->app = $app;
    }

    function installLogFile()
    {
        if (!file_exists(storage_path('logs'))) {
            mkdir(storage_path('logs'), 0755, true);
        }

        $logger = $this->app->make(Logger::class);
        if (!file_exists($logger->getFilePath())) {
            touch($logger->getFilePath());
        }
    }
}
