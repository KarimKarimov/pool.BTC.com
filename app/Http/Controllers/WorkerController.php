<?php

namespace App\Http\Controllers;

use App\Jobs\CreateWorkers;
use App\Models\Worker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;

class WorkerController extends Controller
{

    private const ACCESS_KEY = "r_dZQDQ9FStM9lZ";
    private const PUID = "441535";
    private const STATUS = "all";


    private const API_URL = "https://pool.api.btc.com/v1/worker";

    public function getWorkers(Request $request)
    {
        $get =$request->all();
        $workers = Worker::get_workers($get);

        $days = array_unique($workers['days']) ;
        $data =  $workers['data'];
              
        return view('index',compact('days','data','get'));
      
    }

    public static function getMinnerList()
    {
        dispatch(new CreateWorkers(
            self::API_URL,
            [
                'status' => self::STATUS,
                'access_key' => self::ACCESS_KEY,
                'puid' => self::PUID,
            ]
        ));
    }
}
