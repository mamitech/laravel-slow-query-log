<?php

namespace Mamitech\SlowQueryLog\Tests;

use Illuminate\Support\Facades\Artisan;

class MigrationTest extends TestCase
{
    /** @test */
    public function it_register_the_migration()
    {
        $migration =
            array_filter(
                $this->app['migrator']->paths(),
                function ($path) {
                    return str_contains($path, 'create_laravel_slow_query_log_table.php');
                }
            );
        $this->assertNotEmpty($migration);
    }

    /** @test */
    public function the_migration_is_valid()
    {
        Artisan::call('migrate');
        $this->assertTrue(true); # to remove 'risky test' warning on phpunit
    }

    protected function defineEnvironment($app)
    {
        parent::defineEnvironment($app);
        $app['config']->set('slow-query-log.storage', 'database');
    }
}
