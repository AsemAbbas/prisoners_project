<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->string('news_title')->nullable();
            $table->string('news_photo')->nullable();
            $table->string('news_url')->nullable();
            $table->foreignId('news_type_id')->nullable()->constrained()->cascadeOnDelete();
            $table->boolean('on_slider')->default(false)->nullable();
            $table->text('news_short_description')->nullable();
            $table->longText('news_long_description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};
