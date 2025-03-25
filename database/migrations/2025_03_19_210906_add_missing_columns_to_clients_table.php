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
        Schema::table('clients', function (Blueprint $table) {
            // Add missing columns if they don't exist
            if (!Schema::hasColumn('clients', 'address')) {
                $table->text('address')->nullable();
            }
            
            if (!Schema::hasColumn('clients', 'notes')) {
                $table->text('notes')->nullable();
            }
            
            if (!Schema::hasColumn('clients', 'birth_date')) {
                $table->date('birth_date')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn(['address', 'notes', 'birth_date']);
        });
    }
};
