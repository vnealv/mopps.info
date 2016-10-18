<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\ParkingSession;

class ClearActiveParkingSession extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'api:clearActive';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update isActive column in ParkingSession';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $parking_sessions = ParkingSession::where('isActive', 1)->get();

        $count = 0;
        foreach ($parking_sessions AS $parking_session){
            if(time() >= (int)$parking_session->end_unixtime){
                $parking_session->isActive = 0;
                $parking_session->save();
                $count++;
            }
        }

        $this->info("[".date("Y-m-d H:i:s")."] " .$count . ' Parking sessions has been updated successfully.'.PHP_EOL);
    }
}
