<?php
namespace Vynhart\SlowQueryLog\Models;

use Illuminate\Database\Eloquent\Model;

class SlowQuery extends Model
{
    protected $table = 'laravel_slow_query_log';
}