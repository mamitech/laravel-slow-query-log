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
            $table->unsignedInteger('time')->index();
            $table->text('sql');
            $table->text('path')->index();
            $table->text('traces');
            $table->dateTime('created_at')->index();
        });
    }

    public function down()
    {
        Schema::drop(self::TableName);
    }
}