<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id')->nullable()->constrained()->onDelete('set null');
            $table->string('name');
            $table->string('ward_number')->unique();
            $table->enum('type', ['general', 'icu', 'icu_ccu', 'private', 'semi_private', 'pediatric', 'maternity'])
                  ->default('general');
            $table->integer('total_beds')->default(0);
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('beds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ward_id')->constrained()->onDelete('cascade');
            $table->string('bed_number');
            $table->enum('status', ['available', 'occupied', 'maintenance', 'reserved'])->default('available');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->unique(['ward_id', 'bed_number']);
        });

        Schema::create('admissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->foreignId('doctor_id')->constrained()->onDelete('cascade');
            $table->foreignId('ward_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('bed_id')->nullable()->constrained()->onDelete('set null');
            $table->string('admission_number')->unique();
            $table->timestamp('admission_date');
            $table->timestamp('discharge_date')->nullable();
            $table->enum('status', ['admitted', 'discharged', 'transferred'])->default('admitted');
            $table->text('admission_reason');
            $table->text('discharge_summary')->nullable();
            $table->enum('discharge_condition', ['improved', 'recovered', 'referred', 'deceased', 'lama'])->nullable();
            $table->timestamps();
        });

        Schema::create('nursing_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->foreignId('nurse_id')->constrained()->onDelete('cascade');
            $table->foreignId('admission_id')->nullable()->constrained()->onDelete('set null');
            $table->text('note');
            $table->timestamp('noted_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nursing_notes');
        Schema::dropIfExists('admissions');
        Schema::dropIfExists('beds');
        Schema::dropIfExists('wards');
    }
};
