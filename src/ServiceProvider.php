<?php

namespace Vynhart\SlowQueryLog;

use Illuminate\Database\Connection;
use Illuminate\Database\Events\QueryExecuted;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    const ConfigName = 'slow-query-log';

    private $config;

    public function boot()
    {
        $this->setConfig();
        if ($this->config['slow-query-log.enabled'] === true) {
            $this->checkLogDir();
            $this->setListener();
        }
    }

    private function setConfig()
    {
        $confPath = __DIR__ . '/../config/' . self::ConfigName . '.php';
        $this->publishes([
            $confPath => config_path(self::ConfigName . '.php')
        ]);
        $this->mergeConfigFrom($confPath, self::ConfigName);
        $this->config = $this->app['config'];
    }

    private function checkLogDir()
    {
        if (!file_exists(storage_path('logs'))) {
            mkdir(storage_path('logs'), 0755, true);
        }
    }

    private function setListener()
    {
        $conn = $this->app->make(Connection::class);
        $conn->enableQueryLog();
        $conn->listen(function (QueryExecuted $query) {
            $fname = 'slow-query-' . date('Y-m-d');
            $file = fopen(storage_path('logs/' . $fname), 'a');
            fwrite($file, $query->sql . "\n");
        });
    }
}
