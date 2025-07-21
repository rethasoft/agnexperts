<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('combi_discounts', function (Blueprint $table) {
            $table->id();
            $table->json('service_ids'); // [1,2] gibi
            $table->string('discount_type'); // 'percentage' veya 'fixed'
            $table->decimal('discount_value', 8, 2);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('combi_discounts');
    }
}; 