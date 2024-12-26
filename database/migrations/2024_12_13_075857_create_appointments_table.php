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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->foreignId('patient_id')->references('id')->on('patients');
            $table->foreignId('doctor_id')->references('id')->on('doctors');
            $table->foreignId('service_id')->references('id')->on('services');
            $table->foreignId('schedule_id')->references('id')->on('schedules');
            $table->foreignId('slot_id')->references('id')->on('slots');
            $table->enum('status', ['pending','booked', 'cancelled', 'completed','rescheduled'])->default('pending');
            $table->longText("description");
            $table->string('prescription')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
