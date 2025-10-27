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
            // Separate address fields
            $table->string('street')->nullable()->after('phone');
            $table->string('house_number')->nullable()->after('street');
            $table->string('house_number_addition')->nullable()->after('house_number');
            $table->string('postal_code')->nullable()->after('house_number_addition');
            $table->string('city')->nullable()->after('postal_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn([
                'street',
                'house_number', 
                'house_number_addition',
                'postal_code',
                'city'
            ]);
        });
    }
};
