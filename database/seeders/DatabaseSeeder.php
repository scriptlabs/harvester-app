<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::factory(10)->create();

        DB::table('sensors')->insert([
            'uid' => 'sensor-1',
            'active' => true,
            'label' => "Sensor 1",
            'description' => "Sensor 1",
            'types' => @json_encode(['temperature', 'humidity', 'pressure']),
            'device_protocol' => 'bme280',
            'device_address' => '1',
            'metadata' => null,
            'created_at' => now()
        ]);
    }
}
