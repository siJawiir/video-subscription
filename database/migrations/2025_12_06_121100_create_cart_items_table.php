<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id('cart_item_id');

            $table->unsignedBigInteger('cart_id');
            $table->unsignedBigInteger('video_id');
            $table->integer('duration_seconds');
            $table->decimal('price', 12, 2);

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('cart_id')->references('cart_id')->on('carts')->cascadeOnDelete();
            $table->foreign('video_id')->references('video_id')->on('videos')->cascadeOnDelete();
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};
