<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'slow-query-log',
    'namespace' => 'Vynhart\SlowQueryLog\Controllers'
], function() {
    Route::get('/', 'DashboardController@index');
});