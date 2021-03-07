<?php

namespace App\Console\Commands;

use App\Models\Log;
use App\Models\Sensor;
use App\Models\SensorData;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SensorRead extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sensor:read';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Read sensor data.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        //
        $this->output->title('Sensor data');

        $data = Sensor::executeReadSensorData();
        if(!empty($data)) {
            $this->output->success('Reading data successful!');

            $this->info(json_encode($data, JSON_PRETTY_PRINT));

            return true;
        }

        //Logs::error('Sensor read error', 'sensors', []);

        $this->output->error('Error reading data!');
        return false;
    }

}
