<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration {
    public function up() {
        Schema::create('video_access', function (Blueprint $table) {
            $table->id('video_access_id');

            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('video_id');
            $table->unsignedBigInteger('order_item_id')->nullable();

            $table->integer('purchased_time_seconds');
            $table->integer('used_time_seconds')->default(0);
            $table->integer('remaining_time_seconds');
            $table->tinyInteger('status')->default(1); // 0:blocked,1:active,3:expired
            $table->timestamp('activated_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('user_id')->on('users')->cascadeOnDelete();
            $table->foreign('video_id')->references('video_id')->on('videos')->cascadeOnDelete();
            $table->foreign('order_item_id')->references('order_item_id')->on('order_items')->nullOnDelete();

            $table->index(['user_id', 'video_id', 'status']);
        });
    }

    public function down() {
        Schema::dropIfExists('video_access');
    }
};
