<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class SocketCommand extends Model
{
    use SoftDeletes;
    /**
     * The models attributes.
     *
     * @var array
     */
    protected $attributes = [];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uid',
        'command_value',
        'metadata',
        'status_code',
        'status_message',
        'executed_at',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    /**
     * The attributes that are converted to their data types.
     *
     * @var array
     */
    protected $casts = [
        'command_value' => 'bool',
        'metadata' => 'array'
    ];
    /**
     * The attributes that are converted to date/time objects.
     *
     * @var array
     */
    protected $dates = [
        'executed_at',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'id', 'deleted_at'
    ];

    /**
     * The attributes are added virtual to the model's JSON form.
     *
     * @var array
     */
    protected $appends = [];

    public static function boot() {
        parent::boot();
        self::creating(function ($model) {
            if(empty($model->uid)) {
                do {
                    $model->uid = (string)Str::random(24). time();
                } while(self::where(['uid' => $model->uid])->first());
            }
        });
    }

    /**
     * Get the socket that executes the command.
     */
    public function socket()
    {
        return $this->belongsTo('App\Models\Socketes');
    }

    protected function updateStatus($statusCode, $statusMessage='', $save=false) {
        $this->status_code = (int)$statusCode;
        if(!empty($statusMessage)) {
            $this->status_message = $statusMessage;
        }
        if($save) {
            $this->saveOrFail();
        }
        return $this;
    }

    public static function executeSocketToggleState($socketPinBCM) {
        $socketPinBCM = (int)$socketPinBCM;
        if($socketPinBCM<0 || $socketPinBCM>30) {
            return null;
        }
        $socketState = (bool)self::executeSocketReadState($socketPinBCM);
        try {
            $cmd = 'gpio -g mode '.$socketPinBCM.' out && gpio -g write '.$socketPinBCM . ' ' . ($socketState ? '0' : '1');

            exec($cmd, $output, $returnCode);
            return $socketState;
        } catch (\Exception $e) {
            $output = null;
        }
        return null;
    }
    public static function executeSocketChangeState($socketPinBCM, $socketState) {
        $socketPinBCM = (int)$socketPinBCM;
        if($socketPinBCM<0 || $socketPinBCM>30) {
            return null;
        }
        $socketState = (bool)$socketState;
        try {
            $output = null;
            $returnCode = 0;
            $cmd = 'gpio -g mode '.$socketPinBCM.' out && gpio -g write '.$socketPinBCM . ' ' . ($socketState ? '1' : '0');
            exec($cmd, $output, $returnCode);
            return $socketState;
        } catch (\Exception $e) {
        }
        return null;
    }
    public static function executeSocketReadState($socketPinBCM) {
        $socketPinBCM = (int)$socketPinBCM;
        if($socketPinBCM<0 || $socketPinBCM>30) {
            return null;
        }
        try {
            $output = shell_exec('gpio -g mode '.$socketPinBCM.' out && gpio -g read '.$socketPinBCM);
            $output = str_replace("\n", "", $output);
            $output = str_replace("\t", "", $output);
            $output = (int)$output;
            return $output ? true : false;
        } catch (\Exception $e) {

        }
        return null;
    }
}
