<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id('order_item_id');

            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('video_id');
            $table->integer('duration_seconds');
            $table->decimal('price', 12, 2);

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('order_id')->references('order_id')->on('orders')->cascadeOnDelete();
            $table->foreign('video_id')->references('video_id')->on('videos')->cascadeOnDelete();
        });                                                     
    }

    public function down() {
        Schema::dropIfExists('order_items');
    }
};
