<?php
namespace Vynhart\SlowQueryLog\Tests;

use Orchestra\Testbench\TestCase as BenchTestCase;

class TestCase extends BenchTestCase
{
    protected function getFilePath()
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

}