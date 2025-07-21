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
        Schema::create('employe_events', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('keuringen_id');
            $table->unsignedBigInteger('employee_id');
            $table->string('title');
            $table->dateTime('start');
            $table->dateTime('end');
            $table->string('color', 7)->nullable(); // Stores color in HEX format like #FFFFFF
            $table->timestamps();

            $table->foreign('employee_id')->references('id')->on('employes')->onDelete('cascade'); // Ensures referential integrity
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employe_events');
    }
};
