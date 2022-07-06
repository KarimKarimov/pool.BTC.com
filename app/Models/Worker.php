<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Worker extends Model
{
    use HasFactory;
    protected $fillable = ['worker_id', 'worker_name', 'date', 'hashrate', 'reject'];

    public static function get_workers($request)
    {
        $rate = $request['rate'];
        $consumption = $request['consumption'];
        $date_start = $request['date_start'];
        $date_end = $request['date_end'];

        $days = [];

        $workers = Worker::whereBetween('created_at', [$date_start . " 00:00:00", $date_end . " 23:59:59"])->get()->toArray();

        $sumHashWorkers = Worker::whereBetween(
            'created_at',
            [$date_start . " 00:00:00", $date_end . " 23:59:59"]
        )
            ->select("worker_id", "worker_name", "hashrate", DB::raw("SUM((".$rate."*".$consumption."*24/13.5)*hashrate) as total"))
            ->groupBy("worker_id")
            ->get()
            ->toArray();

        foreach ($workers as $worker) {

            $worker['sum_in_dey'] = round(($rate * $consumption * 24 / 13.5) * $worker['hashrate'],2);
            $id = $worker['worker_id'];
            $workers[$id][] = $worker;

            $startTime   = strtotime($worker['created_at']);
            $days[] = date("Y-m-d", $startTime);
            
        }

        foreach ($sumHashWorkers as $key => $value) {
            $id = $value['worker_id'];
            $sumHashWorkers[$key]['data'] = $workers[$id];
        }

        return 
        [
            "days" =>  $days, 
            "data" => $sumHashWorkers,
        ];
    }
}
