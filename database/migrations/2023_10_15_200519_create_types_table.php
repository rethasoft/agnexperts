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
        Schema::create('types', function (Blueprint $table) {
            $table->id();
            $table->integer('tenant_id');
            $table->integer('category_id')->default(0);
            $table->string('name');
            $table->string('short_name');
            $table->integer('quantity')->default(1);
            $table->decimal('price', 15, 2);
            $table->integer('extra')->default(0);
            $table->decimal('extra_price', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('types');
    }
};
