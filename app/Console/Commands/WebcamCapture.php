<?php

namespace App\Console\Commands;

use App\Models\Capture;
use App\Models\Log;
use Illuminate\Console\Command;

class WebcamCapture extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'webcam:capture';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Capture image.';

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
        $datetime = date('Y-m-d H:i:s');
        $this->output->title('Webcam capture');
        $this->output->comment('Webcam is taking a picture...');
        $output = null;
        $returnCode = 0;
        $capture = Capture::executeWebcamCapture(true, $output, $returnCode);
        if($capture) {
            $this->output->success('Webcam capture successful!');

            $this->info('Capture:');
            $this->output->text('ID: '.$capture->id);
            $this->output->text('File path: '.$capture->folder_name);
            $this->output->text('File name: '.$capture->file_name);
            $this->output->text('File size: '.$capture->file_size);
            $this->output->text('Created at: '.$capture->created_at);

            $this->info('Output:');
            $this->output->text($output);
            return true;
        }
        $this->output->error('Webcam capture error!');

        if($returnCode===-1) {
            $this->info('Saving capture to database failed!');

            Log::error('Webcam capture error', 'captures', [
                'datetime' => $datetime,
                'output' => $output,
                'returnCode' => $returnCode
            ]);
        }

        $this->info('Output:');
        $this->output->text($output);

        Log::info('Webcam capture not successful', 'captures', [
            'datetime' => $datetime,
            'output' => $output,
            'returnCode' => $returnCode
        ]);

        return false;
    }

}
