<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration {
    public function up() {
        Schema::create('users', function (Blueprint $table) {
            $table->id('user_id');
            $table->string('username', 100)->unique();
            $table->string('password');
            $table->tinyInteger('role')->default(1); // 0: admin, 1: customer

            $table->timestamps();
            $table->softDeletes();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->index(['created_by', 'updated_by', 'deleted_by']);
        });

    }

    public function down() {
        Schema::dropIfExists('users');
    }
};
