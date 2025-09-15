<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('inspections', function (Blueprint $table) {
            $table->unsignedBigInteger('combi_discount_id')->nullable()->after('total');
            $table->string('combi_discount_type')->nullable()->after('combi_discount_id');
            $table->decimal('combi_discount_value', 8, 2)->nullable()->after('combi_discount_type');
            $table->decimal('combi_discount_amount', 10, 2)->nullable()->after('combi_discount_value');
        });
    }

    public function down()
    {
        Schema::table('inspections', function (Blueprint $table) {
            $table->dropColumn([
                'combi_discount_id',
                'combi_discount_type',
                'combi_discount_value',
                'combi_discount_amount',
            ]);
        });
    }
}; 