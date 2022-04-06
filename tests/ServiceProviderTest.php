<?php
namespace Mamitech\SlowQueryLog\Tests;

use Mamitech\SlowQueryLog\LogFile;

class ServiceProviderTest extends TestCase
{
    /** @test */
    public function it_spawns_the_logs_directory_on_http_request()
    {
        $this->get('/hi')
            ->assertStatus(200);

        $this->assertDirectoryExists(storage_path('logs'));
        $this->assertTrue(file_exists((new LogFile)->getFilePath()));
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
}
