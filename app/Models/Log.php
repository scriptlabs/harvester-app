<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Log extends Model
{
    const UPDATED_AT = null;

    const PRIORITY_DEBUG = 0;
    const PRIORITY_INFO = 1;
    const PRIORITY_WARNING = 2;
    const PRIORITY_ERROR = 3;
    const PRIORITY_CRITICAL = 4;

    const CATEGORY_DEFAULT = 'default';
    const CATEGORY_DEBUG = 'debug';
    const CATEGORY_EXCEPTION = 'exception';
    const CATEGORY_ERROR = 'error';
    const CATEGORY_INTERNAL = 'internal';
    const CATEGORY_SERVICE = 'service';
    const CATEGORY_DEVICE = 'device';
    const CATEGORY_OTHER = 'other';
    /**
     * The models attributes.
     *
     * @var array
     */
    protected $attributes = [
        'priority',
        'category',
        'message',
        'metadata'
    ];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'priority',
        'category',
        'message',
        'metadata'
    ];
    /**
     * The attributes that are converted to their data types.
     *
     * @var array
     */
    protected $casts = [
        'metadata' => 'array'
    ];
    /**
     * The attributes that are converted to date/time objects.
     *
     * @var array
     */
    protected $dates = [
        'created_at'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The attributes are added virtual to the model's JSON form.
     *
     * @var array
     */
    protected $appends = [];
    /**
     * Logs get written if the log priority is equal or above this min priority.
     *
     * @var int
     */
    protected static $minPriority = self::PRIORITY_DEBUG;
    public static function setMinPriority($minPriority=null) {
        if($minPriority>=self::PRIORITY_DEBUG && $minPriority<=self::PRIORITY_CRITICAL) {
            self::$minPriority = $minPriority;
        }
        return self::$minPriority;
    }

    public static function boot() {
        parent::boot();
    }

    public static function log($message, $priority=self::PRIORITY_DEBUG, $category=self::CATEGORY_DEBUG, $data=null) {
        if($priority>=self::PRIORITY_DEBUG && $priority<=self::PRIORITY_CRITICAL) {
            $priority = self::PRIORITY_DEBUG;
        }
        if(empty($priority)) {
            $priority = self::PRIORITY_DEBUG;
        }
        if(empty($category)) {
            $category = self::CATEGORY_DEFAULT;
        }
        if($priority<self::$minPriority) {
            return null;
        }

        try {
            $log = new self();
            $log->priority = (int)$priority;
            $log->category = strtolower(trim($category));
            $log->message = $message;
            $log->metadata = $data;
            $log->save();

            return $log;
        } catch (\Exception $e) {}
        return null;
    }

    public static function debug($message, $category=self::CATEGORY_DEFAULT, $data=null) {
        return self::log($message, self::PRIORITY_DEBUG, $category, $data);
    }

    public static function info($message, $category=self::CATEGORY_DEFAULT, $data=null) {
        return self::log($message, self::PRIORITY_INFO, $category, $data);
    }

    public static function warning($message, $category=self::CATEGORY_DEFAULT, $data=null) {
        return self::log($message, self::PRIORITY_WARNING, $category, $data);
    }

    public static function error($message, $category=self::CATEGORY_DEFAULT, $data=null) {
        return self::log($message, self::PRIORITY_ERROR, $category, $data);
    }

    public static function critical($message, $category=self::CATEGORY_DEFAULT, $data=null) {
        return self::log($message, self::PRIORITY_CRITICAL, $category, $data);
    }

}
