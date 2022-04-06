<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'slow-query-log',
    'namespace' => 'Mamitech\SlowQueryLog\Controllers'
], function() {
    Route::get('/', 'DashboardController@index');
});