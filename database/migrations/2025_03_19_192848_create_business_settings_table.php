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
        // Only create if it doesn't exist already
        if (!Schema::hasTable('business_settings')) {
            Schema::create('business_settings', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained();
                $table->string('business_name')->nullable();
                $table->string('business_address')->nullable();
                $table->string('business_phone')->nullable();
                $table->string('business_email')->nullable();
                $table->string('timezone')->default('UTC');
                $table->time('business_hours_start')->default('09:00:00');
                $table->time('business_hours_end')->default('17:00:00');
                $table->json('business_days')->nullable();
                $table->integer('appointment_interval')->default(15);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('business_settings');
    }
};
