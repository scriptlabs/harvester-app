<?php

namespace App\Console\Commands;

use App\Models\Capture;
use App\Models\Log;
use App\Models\Sensor;
use App\Models\SensorData;
use App\Models\Socket;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class SocketToggle extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'socket:toggle {socketUid}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Toggle sockets (relays).';

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
        $this->output->title('Socket test');
        try {

            $socketUid = $this->argument('socketUid');
            /** @var Socket $socket */
            $socket = Socket::where(['uid' => $socketUid])->first();
            if(!$socket) {
                $this->output->error('Socket UID is invalid.');
                return false;
            }
            //$output = shell_exec('gpio -g mode 5 out && gpio -g write 5 1');
            $active = $socket->toggleState()->getActiveAttribute();
            //$output = Sensors::getCurrentValueByType('humidity');
            $this->info("Socket state is: ".($active?'ON':'OFF'));
            $this->output->success('Socket state changed successfully.');
            return true;
        } catch (\Exception $e) {
            $this->output->error('Socket state change error.');

            Log::error('Socket state change error', 'socket', [
                'exception' => $e
            ]);
        }
        return false;
    }

}
