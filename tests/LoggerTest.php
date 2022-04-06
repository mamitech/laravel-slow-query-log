<?php

namespace Mamitech\SlowQueryLog\Tests;

use Illuminate\Database\Events\QueryExecuted;
use Mamitech\SlowQueryLog\Logger;

class LoggerTest extends TestCase
{
    /** @test */
    public function it_doesnt_logs_the_query_when_below_threshold()
    {
        $this->query->time = 50;
        $this->logger->log($this->query);

        $lineCount = count(file($this->getFilePath()));
        $this->assertEquals(0, $lineCount);
    }

    /** @test */
    public function it_logs_the_query_when_above_threshold()
    {
        $this->query->time = 150;
        $this->query->sql = 'some sql has been executed';
        $this->logger->log($this->query);

        $lineCount = count(file($this->getFilePath()));
        $this->assertEquals(1, $lineCount);
        $this->assertStringContainsString($this->query->sql, file_get_contents($this->getFilePath()));
    }

    protected function setUp(): void
    {
        parent::setUp();

        // clean up file
        if (file_exists($this->getFilePath())) {
            unlink($this->getFilePath());
            touch($this->getFilePath());
        }

        $this->query = \Mockery::mock(QueryExecuted::class);
        $this->logger = new Logger;
    }
}