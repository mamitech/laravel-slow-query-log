<?php

namespace Vynhart\SlowQueryLog;

use Illuminate\Database\Connection;
use Illuminate\Database\Events\QueryExecuted;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    const ConfigName = 'slow-query-log';

    private $config;

    public function register()
    {
        $this->app->singleton(Logger::class);
    }

    public function boot()
    {
        $this->setConfig();
        if ($this->config['slow-query-log.enabled'] !== true) {
            return;
        }

        $this->installDir();
        $this->setMigration();
        $this->setQueryListener();
        $this->setDashboard();
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

    private function installDir()
    {
        (new FileInstaller($this->app))->installDir();
    }

    private function setMigration()
    {
        # only set migration if the config is set to use
        #   database to log
        if (!$this->config['slow-query-log.storage'] == 'database'){
            return;
        }

        $files = $this->getAllMigrationFiles();
        $this->loadMigrationsFrom($files);
    }

    private function getAllMigrationFiles(): array
    {
        $files = [];
        foreach (
            array_diff(
                scandir(__DIR__ . '/../database'), ['..', '.']
            ) as $fileName
        ) {
            $files[] = __DIR__ . '/../database/' . $fileName;
        }
        return $files;
    }

    private function setQueryListener()
    {
        $conn = $this->app->make(Connection::class);
        $conn->enableQueryLog();

        $logger = $this->app->make(Logger::class);
        $conn->listen(function (QueryExecuted $query) use ($logger) {
            $logger->log($query);
        });
    }

    private function setDashboard()
    {
        $this->loadRoutesFrom(__DIR__.'/../routes.php');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'slow-query-log');
    }
}
