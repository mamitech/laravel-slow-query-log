<?php
namespace Vynhart\SlowQueryLog\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Vynhart\SlowQueryLog\Logger;
use Vynhart\SlowQueryLog\LogFile;

class DashboardController extends BaseController
{
    public function index()
    {
        $filePath = (new LogFile)->getFilePath();
        $data = [];
        if (file_exists($filePath)) {
            $data = collect(explode(
                Logger::Separator,
                file_get_contents($filePath)
            ))->map(function($row) {
                return json_decode($row);
            })->filter(function($row) {
                return !empty($row) && !empty($row->traces);
            })->sortByDesc('time');
        }
        return view('slow-query-log::dashboard', ['data' => $data]);
    }
}