<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
            $table->foreignId('academic_year_id')->constrained('academic_years')->cascadeOnDelete();
            $table->foreignId('class_id')->constrained('classes')->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete();
            $table->foreignId('teacher_id')->constrained('users')->cascadeOnDelete();
            $table->tinyInteger('day_of_week'); // 1=Mon … 5=Fri
            $table->time('start_time');
            $table->time('end_time');
            $table->string('room')->nullable();
            $table->timestamps();

            // A class can't have two lessons at the same time
            $table->unique(['class_id', 'day_of_week', 'start_time'], 'uq_class_slot');
            // A teacher can't be in two places at once
            $table->unique(['teacher_id', 'day_of_week', 'start_time'], 'uq_teacher_slot');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
