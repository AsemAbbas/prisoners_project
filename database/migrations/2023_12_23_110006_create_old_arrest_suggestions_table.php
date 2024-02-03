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
        Schema::create('old_arrest_suggestions', function (Blueprint $table) {
            $table->id();
            $table->string('suggestion_status')->index()->nullable();//enum
            $table->foreignId('prisoner_suggestion_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('prisoner_id')->nullable()->constrained()->cascadeOnDelete();
            $table->date('old_arrest_start_date')->index()->nullable();
            $table->date('old_arrest_end_date')->index()->nullable();
            $table->string('arrested_side')->nullable();//enum
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('old_arrest_suggestions');
    }
};
