<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();

            $table->foreignId('student_id')
                ->constrained('students')
                ->cascadeOnDelete();

            $table->foreignId('school_class_id')
                ->constrained('school_classes')
                ->cascadeOnDelete();

            $table->foreignId('teacher_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->date('attendance_date');

            $table->enum('status', [
                'present',
                'absent',
                'late'
            ]);

            $table->text('remarks')->nullable();

            $table->timestamps();

            $table->unique([
                'student_id',
                'school_class_id',
                'attendance_date'
            ], 'unique_student_class_attendance');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
