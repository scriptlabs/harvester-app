<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCapturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('captures', function (Blueprint $table) {
            $table->id();
            $table->string('uid')->unique();
            $table->string('video_source');
            $table->string('file_name');
            $table->string('file_path');
            $table->integer('file_size')->default(0);
            $table->string('file_extension');
            $table->string('file_mimetype');
            $table->json('file_metadata')->nullable();
            $table->timestamp('file_removed_at')->nullable();
            $table->json('cached_data')->nullable();
            $table->json('metadata')->nullable();
            $table->string('public_url')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamp('uploaded_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('capture');
    }
}
