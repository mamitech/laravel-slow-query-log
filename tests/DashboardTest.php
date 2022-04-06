<?php

namespace Mamitech\SlowQueryLog\Tests;

class DashboardTest extends TestCase
{
    /** @test */
    public function dashboard_works()
    {
        $this->get('/slow-query-log')
            ->assertSuccessful()
            ->assertSeeText('Slow Queries');
    }
}
