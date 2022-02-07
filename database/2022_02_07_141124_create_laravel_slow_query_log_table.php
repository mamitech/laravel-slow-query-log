<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLaravelSlowQueryLogTable extends Migration
{
    const TableName = 'laravel_slow_query_log';

    public function up()
    {
        Schema::create(self::TableName, function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('time');
            $table->string('app_env');
            $table->string('path');
            $table->text('sql');
            $table->text('traces');
            $table->timestampTz('created_at');
        });

        Schema::table(self::TableName, function (Blueprint $table) {
            $table->index(['app_env', 'time']);
            $table->index(['created_at', 'app_env']);
            $table->index(['path', 'app_env']);
        });
    }

    public function down()
    {
        Schema::drop(self::TableName);
    }
}