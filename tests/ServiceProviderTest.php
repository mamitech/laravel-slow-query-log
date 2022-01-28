<?php

namespace Vynhart\LaravelSlowQueryLog\Tests;

use Orchestra\Testbench\TestCase;
use Illuminate\Database\Schema\Blueprint;


class ServiceProviderTest extends TestCase
{
    /** @test */
    public function it_spawns_the_logs_directory()
    {
        $this->app['router']->get('hi', function () {
            return 'hi there';
        });

        $this->get('/hi')
            ->assertStatus(200);

        $this->assertDirectoryExists(storage_path('logs'));
    }

    /** @test */
    public function it_logs_the_query()
    {
        $this->app['router']->get('hi', function () {
            \Schema::create('users', function (Blueprint $table) {
                $table->increments('id');
            });

            \DB::table('users')->first();

            return 'hi there';
        });

        $this->get('/hi')->assertStatus(200);
        $this->assertFileExists($this->getFilePath());

        $lineCount = count(file($this->getFilePath()));
        $this->assertGreaterThanOrEqual(1, $lineCount);
    }

    private function getFilePath()
    {
        $fname = 'slow-query-' . date('Y-m-d');
        return storage_path('logs/' . $fname);
    }

    protected function getPackageProviders($_)
    {
        return [
            'Vynhart\SlowQueryLog\ServiceProvider'
        ];
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
    }
}
