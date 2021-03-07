<?php

namespace App\Console\Commands;

use App\Models\Logs;
use App\Models\Sensor;
use App\Models\SensorsData;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class BroadcastSend extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'broadcast:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Broadcast farm service.';

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
     * @return bool
     */
    public function handle(): bool
    {
        //
        $this->output->title('Farm service broadcast');
        $this->output->text('=> Sending broadcast message...');

        $client = stream_socket_client("udp://255.255.255.255:9001", $errno, $errstr);
        if (!$client) {
            echo "ERROR: $errno - $errstr<br />\n";
        } else {
            $uid = bin2hex(random_bytes(32));
            $this->output->text('UID('.strlen($uid).'): '.$uid);
            $request = [
                'uid' => $uid,
                'hostname' => gethostname(),
                'ip4' => gethostbyname(gethostname().'local')
            ];
            $success = stream_socket_sendto($client, json_encode($request), 0);
            if($success) {
                $this->output->success('Message sent ('.$success.')!');
                $response = stream_socket_recvfrom($client, 1024, 0);

                $this->output->text('Response: '.$response);
            }
            else {
                $this->output->error('Message sending error!');
            }
        }
        $this->output->text('Done');
        return true;
    }

}
