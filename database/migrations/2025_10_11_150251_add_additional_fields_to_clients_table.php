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
            // Company information
            $table->string('company_name')->nullable()->after('surname');
            $table->string('btw_number')->nullable()->after('company_name');
            
            // Contact information
            $table->string('contact_person')->nullable()->after('phone');
            $table->string('industry')->nullable()->after('contact_person');
            
            // Notes
            $table->text('internal_notes')->nullable()->after('address');
            $table->text('client_notes')->nullable()->after('internal_notes');
            
            // Billing address (stored as JSON for flexibility)
            $table->json('billing_address')->nullable()->after('client_notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn([
                'company_name',
                'btw_number',
                'contact_person',
                'industry',
                'internal_notes',
                'client_notes',
                'billing_address'
            ]);
        });
    }
};
