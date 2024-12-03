<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('video_category_video', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('video_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained('video_categories')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('video_category_video');
    }
};
