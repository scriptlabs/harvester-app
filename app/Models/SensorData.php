<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SensorData extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sensors_data';
    /**
     * The name of the "updated at" column.
     *
     * @var string
     */
    const UPDATED_AT = null;
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
        'sensor_id',
        'sensor_type',
        'sensor_value',
        'sensor_precision',
        'sensor_unit',
        'sensor_timestamp',
        'sensor_metadata',
        'created_at',

        'dt_dayofweek',
        'dt_hourofday',
        'dt_minuteofhour',
        'dt_dayofmonth',
        'dt_monthofyear',
        'dt_dayofyear',
        'dt_weekofyear',
        'dt_year'
    ];
    /**
     * The attributes that are converted to their data types.
     *
     * @var array
     */
    protected $casts = [
        'sensor_metadata'   => 'array',
        'sensor_timestamp'  => 'timestamp',

        'dt_dayofweek'      => 'integer',
        'dt_hourofday'      => 'integer',
        'dt_minuteofhour'   => 'integer',
        'dt_dayofmonth'     => 'integer',
        'dt_monthofyear'    => 'integer',
        'dt_dayofyear'      => 'integer',
        'dt_weekofyear'     => 'integer',
        'dt_year'           => 'integer'
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
    protected $hidden = [
       'sensor_id'
    ];

    /**
     * The attributes are added virtual to the model's JSON form.
     *
     * @var array
     */
    protected $appends = [
        'value',
        'sensor',
        'trend'
    ];

    public function getValueAttribute() {
        return [
            'int' => $this->getValueIntAttribute(),
            'float' => $this->getValueFloatAttribute(),
            'text' => $this->getValueTextAttribute(),
            'unit' => $this->sensor_unit
        ];
    }

    public function getValueTextAttribute() {
        $value = $this->sensor_value;
        $unit = $this->sensor_unit;
        $precision = $this->sensor_precision;
        if((int)$precision) {
            $value = floatval($value / $precision);
        }
        return $value . ($unit ? $unit : '');
    }

    public function getValueIntAttribute() {
        $value = $this->sensor_value;
        return $value;
    }

    public function getValueFloatAttribute() {
        $value = $this->sensor_value;
        $precision = $this->sensor_precision;
        if((int)$precision) {
            $value = ($value / $precision);
        }
        return floatval($value);
    }

    public function setSensorTimestampAttribute($timestamp)
    {
        if($timestamp) {
            if(is_string($timestamp) || is_integer($timestamp)) {
                $timestamp = new \DateTime($timestamp);
            }
            $this->attributes['sensor_timestamp'] = $timestamp;
            $this->_updateDt();
        }
    }

    public function getTrendAttribute($compareToSensorDataTimestamp=null) {
        $trend = [
            'direction' => 0,
            'difference' => 0
        ];

        if($compareToSensorDataTimestamp!==null) {
            $valueBefore = self::where([
                    ['sensor_type', '=', $this->sensor_type],
                    ['sensor_timestamp', '<=', $compareToSensorDataTimestamp]
                ])
                ->orderBy('id', 'desc')
                ->limit(1)
                ->first();
        }
        else {
            $valueBefore = self::where([
                    ['sensor_type', '=', $this->sensor_type],
                    ['id', '<', $this->id]
                ])
                ->orderBy('id', 'desc')
                ->limit(1)
                ->first();
        }

        if($valueBefore) {
            $direction = ($this->sensor_value <=> $valueBefore->sensor_value);
            $difference = (($this->sensor_value - $valueBefore->sensor_value)/$this->sensor_precision);
            $trend = [
                'direction' => $direction,
                'difference' => $difference,
                'timestamp' => $valueBefore->sensor_timestamp,
                'timediff' => $this->sensor_timestamp - $valueBefore->sensor_timestamp
            ];
        }

        return $trend;
    }

    protected function _updateDt() {
        $timestamp = new \DateTime();
        if(isset($this->attributes['sensor_timestamp'])) {
            $timestamp = $this->attributes['sensor_timestamp'];
        }
        $this->attributes['dt_dayofweek'] = (int)$timestamp->format('N');
        $this->attributes['dt_hourofday'] = (int)$timestamp->format('G');
        $this->attributes['dt_minuteofhour'] = (int)$timestamp->format('m');
        $this->attributes['dt_dayofmonth'] = (int)$timestamp->format('d');
        $this->attributes['dt_monthofyear'] = (int)$timestamp->format('n');
        $this->attributes['dt_dayofyear'] = (int)$timestamp->format('z');
        $this->attributes['dt_weekofyear'] = (int)$timestamp->format('W');
        $this->attributes['dt_year'] = (int)$timestamp->format('Y');

        return $this;
    }

    public function getSensorAttribute() {
        $sensor = Sensor::where(['id' => $this->sensor_id])->firstOrFail();
        return [
            'uid'       => $sensor->uid,
            'label'     => $sensor->label,
            'types'     => $sensor->types,
            'created_at'=> $sensor->created_at
        ];
    }

    public static function getAverageValuesForToday() {
        /*
            SELECT
                   DATE(created_at) as date,
                   HOUR(created_at) as hour,
                   sensor_type as type,
                   AVG(sensor_value)/sensor_precision as value,
                   CONCAT(FORMAT(AVG(sensor_value)/sensor_precision, 2), ' ', sensor_unit) as text
            FROM
                 sensors_data
            WHERE
                  DATE_SUB(created_at, INTERVAL 1 HOUR)
              AND
                  DATE(created_at) = DATE(NOW())
            GROUP BY sensor_type, DATE(created_at), HOUR(created_at)
            ORDER BY id DESC;
         */
    }

    public static function getAverageValuesForLastWeek() {
        /*
            SELECT
                   DATE(created_at) as date,
                   HOUR(created_at) as hour,
                   sensor_type as type,
                   AVG(sensor_value)/sensor_precision as value,
                   CONCAT(FORMAT(AVG(sensor_value)/sensor_precision, 2), ' ', sensor_unit) as text
            FROM
                 sensors_data
            WHERE
                  DATE_SUB(created_at, INTERVAL 1 HOUR)
              AND
                  created_at > DATE_SUB(NOW(), INTERVAL 1 WEEK)
            GROUP BY sensor_type, DATE(created_at), HOUR(created_at)
            ORDER BY id DESC;
         */
    }
}
