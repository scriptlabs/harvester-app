<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Sensor extends Model
{
    use SoftDeletes, HasFactory, Notifiable;
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
        'active',
        'label',
        'description',
        'types',
        'metadata',
        'device_protocol',
        'device_address',
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
        'active'    => 'boolean',
        'metadata'  => 'array',
        'types'     => 'array'
    ];
    /**
     * The attributes that are converted to date/time objects.
     *
     * @var array
     */
    protected $dates = [
        'created_at', 'updated_at', 'deleted_at'
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
        'last_data'
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

    public static function getLastDataAttribute($compareToSensorDataTimestamp=null) {
        $result = [
            'temperature'   => null,
            'humidity'      => null,
            'pressure'      => null
        ];
        /** @var SensorData $temperature */
        $temperature = self::getCurrentDataByType('temperature');
        if($temperature) {
            $result['temperature'] = [
                'sensor_value' => $temperature->getValueAttribute(),
                'sensor_timestamp' => $temperature->created_at,
                'timeago' => [
                    'datetime' => date(DATE_ISO8601, strtotime($temperature->created_at)),
                    'time' => date('H:i:s', strtotime($temperature->created_at))
                ],
                'trend' => $temperature->getTrendAttribute($compareToSensorDataTimestamp)
            ];
        }
        /** @var SensorData $humidity */
        $humidity = self::getCurrentDataByType('humidity');
        if($humidity) {
            $result['humidity'] = [
                'sensor_value' => $humidity->getValueAttribute(),
                'sensor_timestamp' => $humidity->sensor_timestamp,
                'timeago' => [
                    'datetime' => date(DATE_ISO8601, strtotime($humidity->created_at)),
                    'time' => date('H:i:s', strtotime($humidity->created_at))
                ],
                'trend' => $humidity->getTrendAttribute($compareToSensorDataTimestamp)
            ];
        }
        /** @var SensorData $pressure */
        $pressure = self::getCurrentDataByType('pressure');
        if($pressure) {
            $result['pressure'] = [
                'sensor_value' => $pressure->getValueAttribute(),
                'sensor_timestamp' => $pressure->sensor_timestamp,
                'timeago' => [
                    'datetime' => date(DATE_ISO8601, strtotime($pressure->created_at)),
                    'time' => date('H:i:s', strtotime($pressure->created_at))
                ],
                'trend' => $pressure->getTrendAttribute($compareToSensorDataTimestamp)
            ];
        }
        return $result;
    }

    public static function getCurrentDataByType($type, $withUnit=false, $asInteger=false) {
        return SensorData::where('sensor_type', $type)->orderBy('created_at', 'desc')->first();
    }

    public static function getCurrentValueByType($type, $withUnit=false, $asInteger=false) {
        $data = SensorData::where('sensor_type', $type)->orderBy('created_at', 'desc')->first();
        if($data) {
            $value = $data->sensor_value;
            $unit = $data->sensor_unit;
            $precision = $data->sensor_precision;
            if(!$asInteger && (int)$precision) {
                $value = floatval($value / $precision);
            }
            return $value . ($withUnit ? $unit : '');
        }
        return null;
    }

    public static function executeReadSensorData($save=true) {
        $response = [];
        $output = '';
        $binPath = base_path('bin');
        try {
            $timestamp = new \DateTime();
            $output = shell_exec('cd '.$binPath.' && python bme280.py');
            $data = @json_decode($output, true);
            if(is_array($data) && $save) {

                $type = 'temperature';
                if(isset($data[$type])) {
                    $sensorData = new SensorData();
                    $sensorData->sensor_id = 1;
                    $sensorData->sensor_type = $type;

                    $precision = 100;
                    $sensorData->sensor_precision = $precision;
                    $value = intval(($data[$type]*$precision));
                    $sensorData->sensor_value = $value;
                    $sensorData->sensor_unit = '°C';
                    $sensorData->sensor_metadata = null;
                    $sensorData->sensor_timestamp = $timestamp;
                    $sensorData->save();

                    $response[$type] = $sensorData;
                }

                $type = 'humidity';
                if(isset($data[$type])) {
                    $sensorData = new SensorData();
                    $sensorData->sensor_id = 1;
                    $sensorData->sensor_type = $type;

                    $precision = 1000;
                    $sensorData->sensor_precision = $precision;
                    $value = intval(($data[$type]*$precision));
                    $sensorData->sensor_value = $value;
                    $sensorData->sensor_unit = '%';
                    $sensorData->sensor_metadata = null;
                    $sensorData->sensor_timestamp = $timestamp;

                    $sensorData->save();

                    $response[$type] = $sensorData;
                }

                $type = 'pressure';
                if(isset($data[$type])) {
                    $sensorData = new SensorData();
                    $sensorData->sensor_id = 1;
                    $sensorData->sensor_type = $type;

                    $precision = 1000;
                    $sensorData->sensor_precision = $precision;
                    $value = intval(($data[$type]*$precision));
                    $sensorData->sensor_value = $value;
                    $sensorData->sensor_unit = 'hPa';
                    $sensorData->sensor_metadata = null;
                    $sensorData->sensor_timestamp = $timestamp;

                    $sensorData->save();

                    $response[$type] = $sensorData;
                }
            }
        } catch (\Exception $e) {
            $response = [];
            Log::error('Execute read sensor error', 'sensors', [
                'exception' => $e,
                'output' => $output
            ]);
            var_dump($e->getMessage());
        }
        return $response;
    }

    public function getStatistics($intervalMinutes=15, $limit=10, $startDate=null) {
        if($limit<=0 || $limit>1000) {
            $limit = 10;
        }

        $sensorTypesCount = (count($this->types) ? count($this->types) : 1);
        $limit = ($limit * $sensorTypesCount) + (1*$sensorTypesCount); //add current running hour

        $where = [];
        $where[] = ['sensors_id','=',$this->id];
        if($startDate!==null) {
            $where[] = [DB::raw("UNIX_TIMESTAMP(`created_at`)"),'>=',DB::raw("UNIX_TIMESTAMP('".$startDate."')")];
        }
        /** @var Builder $sql */
        $sql = DB::table('sensors_data');
        $sql->select([
            'sensors_id',
            'sensor_type',
            DB::raw('AVG(sensor_value/sensor_precision) as value'),
            DB::raw('FROM_UNIXTIME(CEILING(UNIX_TIMESTAMP(`created_at`)/(60*'.$intervalMinutes.'))*(60*'.$intervalMinutes.')) AS timeslice')
        ]);
        if(count($where)) {
            $sql->where($where);
        }

        $sql->groupBy(
            'sensors_id',
            'sensor_type',
            'timeslice'
        //DB::raw('HOUR(created_at)')
        );

        $sql->orderBy(DB::raw('timeslice'), 'desc');
        $sql->orderBy('sensor_type', 'desc');

        $sql->limit($limit);

        /** @var SensorData[] $result */
        $result = $sql->get();

        $data = [];
        foreach ($result as $element) {
            $date = new \DateTime();
            $date->setTimestamp(strtotime($element->timeslice));
            $data[$date->format('Y-m-d')][$date->format('H:i')][$element->sensor_type] = round($element->value, 2);
        }

        return $data;
    }
}
