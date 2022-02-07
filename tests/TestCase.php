<?php
namespace Vynhart\SlowQueryLog\Tests;

use Orchestra\Testbench\TestCase as BenchTestCase;
use Illuminate\Database\Schema\Blueprint;
use Vynhart\SlowQueryLog\LogFile;

abstract class TestCase extends BenchTestCase
{
    protected function getFilePath()
    {
        return (new LogFile)->getFilePath();
    }

    protected function getPackageProviders($_)
    {
        return [
            'Vynhart\SlowQueryLog\ServiceProvider'
        ];
    }

    protected function setUp(): void
    {
        parent::setUp();

        \Schema::create('notes', function (Blueprint $table) {
            $table->increments('id');
            $table->text('content');
        });

        $this->app['router']->get('hi', function () {
            return 'hi there';
        });
    }

    protected function defineEnvironment($app)
    {
        $config = $app['config'];
        $config->set('database.default', 'testing');
        $config->set(
            'database.connections.testing',
            [
                'driver'   => 'sqlite',
                'database' => ':memory:',
                'prefix'   => '',
            ]
        );
        $app['config']->set('slow-query-log.min-threshold', 100);
    }
}