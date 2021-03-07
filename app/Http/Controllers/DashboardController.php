<?php


namespace App\Http\Controllers;

use App\Models\Sensor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index(Request $request) {
        $cacheSeconds = 60;

        $data = [];

        $data['page'] = '';

        if(!empty($request->dashboardUid)) {
            $data['dashboardUid'] = $request->dashboardUid;
            $data['page'] = $request->dashboardUid;
        }

        $datetime = new \DateTime();
        $datetime->sub(new \DateInterval('PT12H'));

        $data['sensors'] = Cache::remember('dashboard_sensors', $cacheSeconds, function () {
            $intervalMinutes = (60*1);
            $intervalCount = 24;

            $sensorsData = [];
            $sensorsData['statistics'] = [];
            /** @var Sensor[] $sensor */
            $sensors = Sensor::all();
            foreach ($sensors as $sensor) {
                $sensorsData['statistics'][$sensor->uid] = $sensor->getStatistics($intervalMinutes, $intervalCount);
            }
            $sensorsData['current'] = Sensor::getLastDataAttribute();

            return $sensorsData;
        });

        $data['capture'] = Cache::remember('dashboard_capture', $cacheSeconds, function () {
            return Capture::orderBy('created_at', 'DESC')->limit(1)->first();
        });

        $data['sockets'] = Socket::whereNotNull('enabled_at')->get();

        return view('dashboard', $data);
    }

    public function fetchCaptureByDatetime($sensorUid, Request $request) {
        /** @var Sensor $sensor */
        $sensor = Sensor::where([
            ['uid', '=', $sensorUid]
        ])->firstOrFail();

        $sensorsData = $sensor->getLastDataAttribute();

        return $sensorsData;
    }

    public function fetchSensorData($sensorUid, Request $request) {
        /** @var Sensor $sensor */
        $sensor = Sensor::where([
            ['uid', '=', $sensorUid]
        ])->firstOrFail();

        $sensorsData = $sensor->getLastDataAttribute();

        return $sensorsData;
    }

    public function toggleSwitch($switchUid, Request $request) {
        /** @var Socket $switch */
        $switch = Socket::where([
            ['uid', '=', $switchUid]
        ])->whereNotNull('enabled_at')->firstOrFail();

        $switch->toggleState();

        return [
            'uid' => $switch->uid,
            'label' => $switch->label,
            'state' => $switch->switch_state,
            'active' => $switch->getActiveAttribute(),
            'timeago' => [
                'datetime' => date(DATE_ISO8601, strtotime($switch->switch_lastaction_at)),
                'time' => date('H:i:s', strtotime($switch->switch_lastaction_at))
            ],
            'last_action_at' => date('Y-m-d H:i:s', strtotime($switch->switch_lastaction_at))
        ];
    }
}
