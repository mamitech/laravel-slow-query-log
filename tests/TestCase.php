<?php
namespace Vynhart\SlowQueryLog\Tests;

use Orchestra\Testbench\TestCase as BenchTestCase;
use Illuminate\Database\Schema\Blueprint;
use Vynhart\SlowQueryLog\LogFile;

abstract class TestCase extends BenchTestCase
{
    protected function getFilePath()
    {
        return (new LogFile)->getFilePath();
    }

    protected function getPackageProviders($_)
    {
        return [
            'Vynhart\SlowQueryLog\ServiceProvider'
        ];
    }

    protected function setUp(): void
    {
        parent::setUp();
        \Schema::create('notes', function (Blueprint $table) {
            $table->increments('id');
            $table->text('content');
        });
    }
}