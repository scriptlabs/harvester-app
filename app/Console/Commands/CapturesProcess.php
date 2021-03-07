<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CapturesProcess extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'captures:process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Processing of captured images.';

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
    }

}
