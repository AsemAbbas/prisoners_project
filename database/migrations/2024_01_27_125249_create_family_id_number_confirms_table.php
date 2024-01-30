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
        Schema::create('family_id_number_confirms', function (Blueprint $table) {
            $table->id();
            $table->string('id_number')->nullable();
            $table->string('relationship_name')->nullable();
            $table->foreignId('prisoner_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('prisoner_confirm_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('confirm_status')->index()->nullable();//enum
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('family_id_number_confirms');
    }
};
