<?php

namespace App\Jobs;

use App\Models\Worker;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CreateWorkers implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $API_URL;

    protected $ACCESS_KEY;

    protected $PUID;

    protected $STATUS;

    protected $arrParams = [];

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($API_URL, $arrParams)
    {
        $this->API_URL = $API_URL;
        $this->ACCESS_KEY = $arrParams['access_key'];
        $this->PUID = $arrParams['puid'];
        $this->STATUS = $arrParams['status'];

        $this->arrParams = $arrParams;
    }

    //php artisan schedule:work
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $total = 0;
        $pageSize = 0;
        $page = 1;

        do {
            $result = $this->get_minner_data_by_page($page);;

            $result = json_decode($result->body());

            if ($result->err_no != 0)
                return response(['result' => "error"], 500);

            Log::info(print_r($result, true));

            $pageSize = $result->data->page_size;
            $total = $result->data->total_count;
            
            foreach ($result->data->data as $worker) {
                Worker::create(
                    [
                        'worker_id' => $worker->worker_id,
                        'worker_name' => $worker->worker_name,
                        'date' => date("Y-m-d H:i",  $worker->first_share_time),
                        'hashrate' => $worker->shares_1d,
                        'reject' => $worker->reject_percent,
                    ]
                );
            }
            $page++;
        } while ($page * $pageSize <= $total);

        return response(['result' => 'access'], 200);
    }

    public function get_minner_data_by_page($page_id)
    {
        $result = Http::get(
            $this->API_URL,
            [
                'status' => $this->STATUS,
                'page' => $page_id,
                'access_key' => $this->ACCESS_KEY,
                'puid' => $this->PUID,
            ]
        );

        return $result;
    }
}
