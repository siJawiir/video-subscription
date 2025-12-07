<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('orders', function (Blueprint $table) {
            $table->id('order_id');

            $table->unsignedBigInteger('user_id');
            $table->tinyInteger('status')->default(0); // 0: pending, 1: approved, 2: rejected
            $table->decimal('total_amount', 12, 2)->default(0);

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('user_id')->on('users')->cascadeOnDelete();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
        });
    }

    public function down() {
        Schema::dropIfExists('orders');
    }
};