<?php
namespace Vynhart\SlowQueryLog\Tests;

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
    public function it_logs_to_file()
    {
        $this->app['router']->get('hi', function () {
            \DB::table('notes')->first();

            return 'hi there';
        });

        $this->get('/hi')->assertStatus(200);
        $this->assertFileExists($this->getFilePath());

        $lineCount = count(file($this->getFilePath()));
        $this->assertGreaterThanOrEqual(1, $lineCount);
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
