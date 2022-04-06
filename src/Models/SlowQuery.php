<?php
namespace Mamitech\SlowQueryLog\Models;

use Illuminate\Database\Eloquent\Model;

class SlowQuery extends Model
{
    public $timestamps = false;
    protected $table = 'laravel_slow_query_log';
}