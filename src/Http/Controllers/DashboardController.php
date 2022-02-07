<?php
namespace Vynhart\SlowQueryLog\Controllers;

use Carbon\Carbon;
use Illuminate\Routing\Controller as BaseController;
use Vynhart\SlowQueryLog\Logger;
use Vynhart\SlowQueryLog\LogFile;
use Vynhart\SlowQueryLog\Models\SlowQuery;

class DashboardController extends BaseController
{
    public function index()
    {
        $data = [];
        if ($this->isLogInDb()) {
            $data = $this->getDataFromDb();
        } else {
            $data = $this->getDataFromFile();
        }

        return view('slow-query-log::dashboard', ['data' => $data]);
    }

    private function getDataFromFile()
    {
        $filePath = (new LogFile)->getFilePath();
        if (file_exists($filePath)) {
            return collect(explode(
                Logger::Separator,
                file_get_contents($filePath)
            ))->map(function($row) {
                return json_decode($row);
            })->filter(function($row) {
                return !empty($row) && !empty($row->traces);
            })->sortByDesc('time');
        }
        return [];
    }

    private function getDataFromDb()
    {
        $now = Carbon::now();
        $data = SlowQuery::where('app_env', app()->config['app.env'])
            ->whereBetween(
                'created_at',
                [$now->startOfDay()->toDateTimeString(), $now->endOfDay()->toDateTimeString()])
            ->get();
        $data->each( function($_, $key) use ($data) {
            $data[$key]['traces'] = json_decode($data[$key]['traces']);
        });
        return $data;
    }

    private function isLogInDb()
    {
        return app()->config['slow-query-log.storage'] === 'database';
    }

}