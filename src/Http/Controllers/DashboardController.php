<?php
namespace Vynhart\SlowQueryLog\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Vynhart\SlowQueryLog\Logger;

class DashboardController extends BaseController
{
    public function index()
    {
        file_get_contents(app()->make(Logger::class)->getFilePath());
        return view('slow-query-log::dashboard');
    }
}