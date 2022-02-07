<?php

namespace Vynhart\SlowQueryLog\Tests;

class MigrationTest extends TestCase
{
    /** @test */
    public function it_register_the_migration()
    {
        $migration =
            array_filter(
                $this->app['migrator']->paths(),
                function ($path) {
                    return str_contains($path, 'database/create_laravel_slow_query_log_table.php');
                }
            );
        $this->assertNotEmpty($migration);
    }

    protected function defineEnvironment($app)
    {
        parent::defineEnvironment($app);
        $app['config']->set('slow-query-log.storage', 'database');
    }
}
