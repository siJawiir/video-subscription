<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('videos', function (Blueprint $table) {
            $table->id('video_id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('video_url');
            $table->decimal('price', 12, 2)->default(0);
            $table->boolean('is_active')->default(true);

            $table->timestamps();
            $table->softDeletes();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->index(['is_active']);
        });

    }

    public function down() {
        Schema::dropIfExists('videos');
    }
};
