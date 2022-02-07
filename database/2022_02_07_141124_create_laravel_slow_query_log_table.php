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
            $table->text('sql');
            $table->text('path');
            $table->text('traces');
            $table->timestampTz('created_at');
        });

        Schema::table(self::TableName, function (Blueprint $table) {
            $table->index('time');
            $table->index('path');
            $table->index('created_at');
        });
    }

    public function down()
    {
        Schema::drop(self::TableName);
    }
}