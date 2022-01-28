<?php

namespace Vynhart\LaravelSlowQueryLog\Tests;

use Orchestra\Testbench\TestCase;
use Illuminate\Database\Schema\Blueprint;


class QueryLogTest extends TestCase
{
    /** @test */
    public function it_spawns_the_directory()
    {
        $this->app['router']->get('hi', function () {
            return 'hi there';
        });

        $this->get('/hi')
            ->assertStatus(200);

        $this->assertDirectoryExists(storage_path('logs'));
    }

    /** @test */
    public function it_log_the_query()
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

    /** test */
    public function query_is_json_formatted()
    {
        # todo.
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
