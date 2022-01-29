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
            $this->setRoute();
            $this->loadViews();
        }
    }

    public function register()
    {
        $this->app->singleton(Logger::class);
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

        $logger = $this->app->make(Logger::class);
        if (!file_exists($logger->getFilePath())) {
            touch($logger->getFilePath());
        }
    }

    private function setListener()
    {
        $conn = $this->app->make(Connection::class);
        $conn->enableQueryLog();
        $logger = $this->app->make(Logger::class);
        $conn->listen(function (QueryExecuted $query) use ($logger) {
            $logger->log($query);
        });
    }

    private function setRoute()
    {
        $this->loadRoutesFrom(__DIR__.'/../routes.php');
    }

    private function loadViews()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'slow-query-log');
    }
}
