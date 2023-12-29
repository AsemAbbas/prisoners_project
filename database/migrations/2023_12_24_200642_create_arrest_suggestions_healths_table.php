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
        Schema::create('arrest_suggestions_healths', function (Blueprint $table) {
            $table->id();
            $table->string('suggestion_status')->index()->nullable();//enum
            $table->foreignId('prisoner_suggestion_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('prisoner_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('health_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('arrest_suggestion_id')->nullable()->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('arrest_suggestions_healths');
    }
};
