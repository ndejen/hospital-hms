<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medical_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->foreignId('doctor_id')->constrained()->onDelete('cascade');
            $table->foreignId('appointment_id')->nullable()->constrained()->onDelete('set null');
            $table->string('record_number')->unique();
            $table->date('visit_date');
            $table->text('chief_complaint');
            $table->text('history_of_illness')->nullable();
            $table->text('physical_examination')->nullable();
            $table->text('diagnosis');
            $table->text('treatment_plan')->nullable();
            $table->text('follow_up_instructions')->nullable();
            $table->timestamps();
        });

        Schema::create('diagnoses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medical_record_id')->constrained()->onDelete('cascade');
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->foreignId('doctor_id')->constrained()->onDelete('cascade');
            $table->string('icd_code')->nullable();
            $table->string('diagnosis_name');
            $table->text('description')->nullable();
            $table->enum('severity', ['mild', 'moderate', 'severe'])->default('mild');
            $table->date('diagnosed_date');
            $table->boolean('is_chronic')->default(false);
            $table->timestamps();
        });

        Schema::create('vital_signs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->foreignId('nurse_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('appointment_id')->nullable()->constrained()->onDelete('set null');
            $table->decimal('temperature', 5, 2)->nullable()->comment('Celsius');
            $table->string('blood_pressure')->nullable()->comment('e.g. 120/80');
            $table->integer('pulse_rate')->nullable()->comment('per minute');
            $table->integer('respiratory_rate')->nullable()->comment('per minute');
            $table->decimal('oxygen_saturation', 5, 2)->nullable()->comment('percentage');
            $table->decimal('weight', 6, 2)->nullable()->comment('kg');
            $table->decimal('height', 6, 2)->nullable()->comment('cm');
            $table->decimal('bmi', 5, 2)->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('recorded_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vital_signs');
        Schema::dropIfExists('diagnoses');
        Schema::dropIfExists('medical_records');
    }
};
