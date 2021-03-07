<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateSensorsDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sensors_data', function (Blueprint $table) {
            $table->id();
            $table->string('sensor_id');
            $table->string('sensor_type');
            $table->integer('sensor_value');
            $table->integer('sensor_precision');
            $table->string('sensor_unit');
            $table->json('sensor_metadata')->nullable();
            $table->timestamp('sensor_timestamp');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));


            $table->integer('dt_dayofweek');
            $table->integer('dt_hourofday');
            $table->integer('dt_minuteofhour');
            $table->integer('dt_dayofmonth');
            $table->integer('dt_monthofyear');
            $table->integer('dt_dayofyear');
            $table->integer('dt_weekofyear');
            $table->integer('dt_year');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sensors_data');
    }
}
