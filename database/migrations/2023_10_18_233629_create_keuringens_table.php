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
        Schema::create('keuringens', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('client_id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone');
            $table->string('street');
            $table->string('number_bus');
            $table->string('postal_code');
            $table->string('authority');
            $table->integer('type');
            $table->string('expert');
            $table->string('visit_date');
            $table->integer('status');
            $table->text('text')->nullable();
            $table->timestamps();

            // Billing address fields
            $table->boolean('has_billing_address')->default(false);
            $table->string('billing_street')->nullable();
            $table->string('billing_number')->nullable();
            $table->string('billing_box')->nullable();
            $table->string('billing_postal_code')->nullable();
            $table->string('billing_city')->nullable();

            $table->foreign('tenant_id')->references('id')->on('tenants');
            $table->foreign('client_id')->references('id')->on('clients');

            // Add indexes for tenant_id and client_id
            $table->index('tenant_id');
            $table->index('client_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keuringens');
    }
};
