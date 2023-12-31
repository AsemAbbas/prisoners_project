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
        Schema::create('arrests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prisoner_id')->nullable()->constrained()->cascadeOnDelete();
            $table->date('arrest_start_date')->index()->nullable();
            $table->string('arrest_type')->index()->nullable();//enum
            $table->string('judgment_in_lifetime')->index()->nullable();//الحكم بالمؤبدات
            $table->string('judgment_in_years')->index()->nullable();//الحكم بالسنوات
            $table->string('judgment_in_months')->index()->nullable();//الحكم بالأشهر
            $table->foreignId('belong_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('special_case')->index()->nullable();//enum
            $table->text('health_note')->nullable();
            $table->string('social_type')->index()->nullable();//enum
            $table->string('wife_type')->index()->nullable();//enum
            $table->string('number_of_children')->index()->nullable();
            $table->string('education_level')->index()->nullable();//enum
            $table->string('specialization_name')->nullable();
            $table->string('university_name')->nullable();
            $table->string('father_arrested')->nullable();//enum
            $table->string('mother_arrested')->nullable();//enum
            $table->string('husband_arrested')->nullable();//enum
            $table->string('wife_arrested')->nullable();//enum
            $table->integer('brother_arrested')->default(0)->nullable();//number
            $table->integer('sister_arrested')->default(0)->nullable();//number
            $table->integer('son_arrested')->default(0)->nullable();//number
            $table->integer('daughter_arrested')->default(0)->nullable();//number
            $table->string('first_phone_owner')->nullable();
            $table->string('first_phone_number')->nullable();
            $table->string('second_phone_owner')->nullable();
            $table->string('second_phone_number')->nullable();
            $table->string('email')->nullable();
            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('arrests');
    }
};
