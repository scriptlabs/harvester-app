<?php

namespace App\Models;

use App\Helpers;
use App\Providers\DropboxServiceProvider;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\Dropbox\Client as DropboxClient;
use Spatie\FlysystemDropbox\DropboxAdapter;

class Capture extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uid',
        'source_path',
        'file_name',
        'file_path',
        'file_size',
        'file_extension',
        'file_mimetype',
        'file_metadata',
        'file_removed_at',
        'cached_data',
        'metadata',
        'public_url',
        'processed_at',
        'uploaded_at',
        'updated_at',
        'deleted_at'
    ];
    /**
     * The attributes that are converted to their data types.
     *
     * @var array
     */
    protected $casts = [
        'file_metadata' => 'array',
        'cached_data' => 'array',
        'metadata' => 'array'
    ];
    /**
     * The attributes that are converted to date/time objects.
     *
     * @var array
     */
    protected $dates = [
        'file_removed_at', 'created_at', 'updated_at', 'processed_at', 'uploaded_at', 'deleted_at'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'id', 'file_path', 'public_url', 'cached_data', 'file_removed_at', 'deleted_at'
    ];

    /**
     * The attributes are added virtual to the model's JSON form.
     *
     * @var array
     */
    protected $appends = [
        'source',
        'folder_name',
        'sensor_data'
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

    public function getSourceAttribute() {
        if(!empty($this->public_url)) {
            return str_replace('?dl=0','?raw=1', $this->public_url);
        }
        return implode('/',[
            app('request')->getSchemeAndHttpHost(),
            'img',
            'webcam',
            $this->getFolderNameAttribute(),
            $this->file_name
        ]);
    }

    public function getFolderNameAttribute() {
        return str_replace(storage_path('app/webcam').'/', '', $this->file_path);
    }

    public function getSensorDataAttribute() {
        if(!$this->cached_data || !count($this->cached_data)) {
            /** @var SensorData $temperature */
            $temperature = SensorData::where([
                ['sensor_timestamp', '>=', time()],
                ['sensor_type', '=', 'temperature']
            ])->limit(1)->orderBy('id', 'DESC')->first();
            /** @var SensorData $humidity */
            $humidity = SensorData::where([
                ['sensor_timestamp', '>=', time()],
                ['sensor_type', '=', 'humidity']
            ])->limit(1)->orderBy('id', 'DESC')->first();
            /** @var SensorData $pressure */
            $pressure = SensorData::where([
                ['sensor_timestamp', '>=', time()],
                ['sensor_type', '=', 'pressure']
            ])->limit(1)->orderBy('id', 'DESC')->first();

            $this->cached_data = [
                'temperature' => $temperature ? $temperature->getValueAttribute() : 0,
                'humidity' => $humidity ? $humidity->getValueAttribute() : 0,
                'pressure' => $pressure ? $pressure->getValueAttribute() : 0
            ];

            $this->save();
        }
        return $this->cached_data;
    }

    public function getFullLocalFilePath() {
        return $this->file_path . '/' . $this->file_name;
    }

    public function getFileContentFromLocalDisk() {
        $fullPath = $this->getFullLocalFilePath();
        if(file_exists($fullPath) && is_readable($fullPath)) {
            return file_get_contents($fullPath);
        }
        return null;
    }

    public function uploadToDropbox() {
        $dropbox = Storage::disk('dropbox');
        //$localDisk = Storage::disk('local');
        $fileContent = $this->getFileContentFromLocalDisk();
        if(!empty($fileContent)) {
            set_time_limit(300);
            $folderName = 'captures/'.$this->getFolderNameAttribute();
            $remoteFilePath = $folderName.'/'.$this->file_name;
            $success = $dropbox->put($remoteFilePath, $fileContent);
            if($success && empty($this->public_url)) {
                $dropboxClient = new DropboxClient(env('DROPBOX_TOKEN'));
                try {
                    $result = $dropboxClient->createSharedLinkWithSettings($remoteFilePath,[
                        'requested_visibility' => 'public'
                        //'requested_visibility' => 'password',
                        //'link_password' => env('DROPBOX_PUBLIC_PASSWORD')
                    ]);

                    $this->public_url = isset($result['url']) ? $result['url'] : null;
                    $this->uploaded_at = Carbon::now();
                    return $this->save();
                } catch (\Exception $e) {
                    // read the link
                    if(strpos($e->getMessage(), 'shared_link_already_exists')!==false) {
                        $result = $dropboxClient->listSharedLinks($remoteFilePath);
                        if($result && count($result)) {
                            $result = $result[0];
                            $this->public_url = isset($result['url']) ? $result['url'] : null;
                            $this->uploaded_at = Carbon::now();
                            return $this->save();
                        }
                    }
                }
            }
            return false;
        }

        $this->file_removed_at = Carbon::now();
        $this->save();
        return false;
    }

    public function removeLocalFile() {
        $fullPath = $this->getFullLocalFilePath();
        if(file_exists($fullPath)) {
            if(@unlink($fullPath)) {
                $this->file_removed_at = Carbon::now();
                return $this->save();
            }
        }
        return false;
    }

    public static function executeWebcamCapture($save=true, &$output=null, &$returnCode=0) {
        //$output = array();
        //$returnCode = 0;

        $videoSourceId = 'video0';
        $videoSourceDevice = 0;

        $date = date('Y-m-d');
        $timestamp = time();
        $imageExtension = 'jpg';
        $imageSize = "960x720";
        $imagePath = storage_path('app/webcam/'.$date);
        $imageName = $timestamp . '-capture-'.$videoSourceId.'-'.$videoSourceDevice.'.'.$imageExtension;
        $imagePath = rtrim($imagePath, '/');
        if(!is_dir($imagePath)) {
            @mkdir($imagePath, 0775);
        }
        exec('fswebcam -r '.$imageSize.' --jpeg 95 --no-banner '.$imagePath . '/' . $imageName.' 2>&1',$output, $returnCode);

        //TODO: parse $output for errors?

        if(@file_exists($imagePath . '/' . $imageName)) {
            if($save) {
                $cleanOutput = Helpers::cleanConsoleOutput($output);

                $capture = new self();
                $capture->uid = $videoSourceId . '-' . $videoSourceDevice . '-' . $timestamp;
                $capture->video_source = '/dev/'.$videoSourceId . ':' . $videoSourceDevice;
                $capture->file_path = $imagePath;
                $capture->file_name = $imageName;
                $capture->file_size = filesize($imagePath . '/' . $imageName);
                $capture->file_extension = $imageExtension; //pathinfo($imageName, PATHINFO_EXTENSION);
                $capture->file_mimetype = mime_content_type($imagePath . '/' . $imageName);
                $capture->file_metadata = [
                    'width' => 960,
                    'height' => 720
                ];
                $capture->metadata = [
                    'fswebcam' => [
                        'returnCode'    => $returnCode,
                        'output'        => $cleanOutput
                    ]
                ];

                if($capture->save()) {
                    return $capture;
                }
                // model could not get saved
                $returnCode = -1;
            }
            @unlink($imagePath . '/' . $imageName);
        }
        return null;
    }

    public static function removeEmptySubfolders($rootFolderPath, $root=true) {
        if(empty($rootFolderPath)) return false;
        $empty = true;
        foreach (glob($rootFolderPath.DIRECTORY_SEPARATOR . '{,.}[!.,!..]*',GLOB_MARK | GLOB_BRACE) as $file) {
            $empty &= is_dir($file) && self::removeEmptySubfolders($file, false);
        }
        return $empty && !$root && rmdir($rootFolderPath);
    }
}
