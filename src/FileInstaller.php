<?php

namespace Mamitech\SlowQueryLog;

class FileInstaller
{
    function installDir()
    {
        if (!file_exists(storage_path('logs'))) {
            mkdir(storage_path('logs'), 0755, true);
        }
    }
}
