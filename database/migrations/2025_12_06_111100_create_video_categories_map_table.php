<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('video_category_map', function (Blueprint $table) {
            $table->id('video_category_map_id');

            $table->unsignedBigInteger('video_id');
            $table->unsignedBigInteger('video_category_id');

            $table->timestamps();

            $table->foreign('video_id')->references('video_id')->on('videos')->cascadeOnDelete();
            $table->foreign('video_category_id')->references('video_category_id')->on('video_categories')->cascadeOnDelete();

            $table->unique(['video_id', 'video_category_id']);
        });
    }

    public function down() {
        Schema::dropIfExists('video_category_map');
    }
};
