<?php

namespace Mamitech\SlowQueryLog\Tests;

use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mamitech\SlowQueryLog\Logger;
use Mamitech\SlowQueryLog\Models\SlowQuery;

class LoggerToDbTest extends TestCase
{
    use DatabaseMigrations;
    use RefreshDatabase;

    /** @test */
    public function it_logs_the_query_when_above_threshold()
    {
        $this->query->time = 150;
        $this->query->sql = 'some sql has been executed';
        $this->logger->log($this->query);

        $recordCount = SlowQuery::count();
        $this->assertEquals(1, $recordCount);
        $this->assertStringContainsString(SlowQuery::first()->sql, file_get_contents($this->getFilePath()));
    }

    protected function defineEnvironment($app)
    {
        parent::defineEnvironment($app);
        $app['config']->set('slow-query-log.storage', 'database');
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->query = \Mockery::mock(QueryExecuted::class);
        $this->logger = new Logger;
    }
}