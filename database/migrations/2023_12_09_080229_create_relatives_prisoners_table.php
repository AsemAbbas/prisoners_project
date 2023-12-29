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
        Schema::create('relatives_prisoners', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prisoner_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('first_name')->index()->nullable();
            $table->string('second_name')->index()->nullable();
            $table->string('third_name')->index()->nullable();
            $table->string('last_name')->index()->nullable();
            $table->string('identification_number')->index()->nullable();
            $table->foreignId('relationship_id')->nullable()->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('relatives_prisoners');
    }
};
