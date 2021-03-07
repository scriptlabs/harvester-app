<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class Socket extends Model
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
        'label',
        'description',
        'category',
        'socket_pin_bcm',
        'socket_state',
        'socket_lastaction_at',
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
        'socket_inverse' => 'bool'
    ];
    /**
     * The attributes that are converted to date/time objects.
     *
     * @var array
     */
    protected $dates = [
        'created_at', 'socket_lastaction_at', 'updated_at', 'deleted_at'
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
    protected $appends = [
        'active'
    ];

    public static function boot() {
        parent::boot();
        self::creating(function ($model) {
            if(empty($model->uid)) {
                do {
                    $model->uid = (string)Str::random(16);
                } while(self::where(['uid' => $model->uid])->first());
            }
        });
    }
    /**
     * Get the commands for the socket.
     */
    public function comments()
    {
        return $this->hasMany('App\Models\SocketCommand');
    }

    public function getActiveAttribute() {
        $state = (bool)$this->socket_state;
        if($this->socket_inverse) {
            $state = !$state;
        }
        return $state;
    }

    protected function updateLastactionAt($save=false) {
        $this->socket_lastaction_at = Carbon::now();
        if($save) {
            $this->saveOrFail();
        }
        return $this;
    }

    public function syncState() {
        $currentState = $this->readState();
        if((bool)$this->socket_state!=$currentState) {
            // states are different, update database state
            $this->socket_state = $currentState;
            $this->saveOrFail();
        }
        // nothing to sync
        return ((bool)$this->socket_state===(bool)$currentState);
    }

    public function readState() {
        return (bool)SocketCommand::executeSocketReadState($this->socket_pin_bcm);
    }

    public function setState($state) {
        $currentState = SocketCommand::executeSocketChangeState($this->socket_pin_bcm, $state);
        if($currentState==$state) {
            // states has changed, update database state
            $this->socket_state = $currentState;
            $this->updateLastactionAt();
            $this->saveOrFail();
        }
        return $this;
    }

    public function toggleState() {
        $savedState = (bool)$this->socket_state;
        $currentState = SocketCommand::executeSocketChangeState($this->socket_pin_bcm, !$savedState);
        if($currentState!=$savedState) {
            // states has changed, update database state
            $this->socket_state = $currentState;
            $this->updateLastactionAt();
            $this->saveOrFail();
        }
        return $this;
    }

}
