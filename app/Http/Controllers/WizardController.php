<?php


namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;

class WizardController extends Controller
{

    public function index() {
        $output = shell_exec('sh /var/www/html/bin/gpio_readall.sh');
//        $output = nl2br($output);
//        $output = explode('<br />', $output);
        return view('wizard.start', [ 'gpio' => $output ]);
    }
}
