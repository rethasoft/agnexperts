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
            $table->integer('tenant_id');
            $table->unsignedBigInteger('client_id')->default(0);
            $table->string('file_id');
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->string('street');
            $table->integer('number');
            $table->integer('bus');
            $table->string('postal_code');
            $table->string('city');
            $table->integer('status');
            $table->text('text')->nullable();
            $table->integer('province_id')->nullable();
            $table->integer('paid')->default(0);
            $table->integer('payment_status')->default(0);
            $table->timestamps();

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
