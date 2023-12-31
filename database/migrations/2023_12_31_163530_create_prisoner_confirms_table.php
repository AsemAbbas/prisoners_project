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
        Schema::create('prisoner_confirms', function (Blueprint $table) {
            $table->id();
            $table->string('confirm_status')->index()->nullable();//enum
            $table->unsignedBigInteger('prisoner_id')->index()->nullable();
            $table->string('identification_number')->index()->nullable();
            $table->string('first_name')->index()->nullable();
            $table->string('second_name')->index()->nullable();
            $table->string('third_name')->index()->nullable();
            $table->string('last_name')->index()->nullable();
            $table->string('mother_name')->index()->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('gender')->index()->nullable();//enum
            $table->foreignId('city_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('town_id')->nullable()->constrained()->cascadeOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prisoner_confirms');
    }
};
