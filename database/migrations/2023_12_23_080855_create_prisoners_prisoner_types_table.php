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
        Schema::create('prisoners_prisoner_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prisoner_type_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('prisoner_id')->nullable()->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prisoners_prisoner_types');
    }
};
