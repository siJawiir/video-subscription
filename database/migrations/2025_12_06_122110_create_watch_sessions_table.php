<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('watch_sessions', function (Blueprint $table) {
            $table->id('watch_session_id');

            $table->unsignedBigInteger('video_access_id');

            $table->timestamp('started_at');
            $table->timestamp('ended_at')->nullable();
            $table->integer('watched_seconds')->default(0);

            $table->string('device')->nullable();
            $table->string('ip_address', 45)->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('video_access_id')->references('video_access_id')->on('video_access')->cascadeOnDelete();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
        });
    }

    public function down() {
        Schema::dropIfExists('watch_sessions');
    }
};
