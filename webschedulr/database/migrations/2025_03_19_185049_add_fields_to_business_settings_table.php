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
        Schema::table('business_settings', function (Blueprint $table) {
            // Add any missing fields
            if (!Schema::hasColumn('business_settings', 'business_name')) {
                $table->string('business_name')->nullable();
            }
            if (!Schema::hasColumn('business_settings', 'business_email')) {
                $table->string('business_email')->nullable();
            }
            if (!Schema::hasColumn('business_settings', 'business_phone')) {
                $table->string('business_phone')->nullable();
            }
            if (!Schema::hasColumn('business_settings', 'business_address')) {
                $table->text('business_address')->nullable();
            }
            if (!Schema::hasColumn('business_settings', 'business_hours')) {
                $table->text('business_hours')->nullable();
            }
            if (!Schema::hasColumn('business_settings', 'appointment_lead_time')) {
                $table->integer('appointment_lead_time')->default(24);
            }
            if (!Schema::hasColumn('business_settings', 'appointment_duration')) {
                $table->integer('appointment_duration')->default(60);
            }
            if (!Schema::hasColumn('business_settings', 'notification_email')) {
                $table->boolean('notification_email')->default(true);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // You can implement the reverse migration if needed
    }
};
