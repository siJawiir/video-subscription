<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('video_tag_map', function (Blueprint $table) {
            $table->id('video_tag_map_id');

            $table->unsignedBigInteger('video_id');
            $table->unsignedBigInteger('video_tag_id');

            $table->timestamps();

            $table->foreign('video_id')->references('video_id')->on('videos')->cascadeOnDelete();
            $table->foreign('video_tag_id')->references('video_tag_id')->on('video_tags')->cascadeOnDelete();

            $table->unique(['video_id', 'video_tag_id']);
        });
    }

    public function down() {
        Schema::dropIfExists('video_tag_map');
    }
};
