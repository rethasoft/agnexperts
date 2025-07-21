<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inspection_id')->constrained()->onDelete('cascade');
            $table->foreignId('file_id')->nullable()->constrained()->onDelete('set null');
            $table->string('number')->unique();          // Fatura numarası
            $table->decimal('amount', 10, 2);           // Toplam tutar
            $table->decimal('tax_amount', 10, 2);       // KDV tutarı
            $table->string('status');                   // DRAFT, SENT, PAID vs.
            $table->date('issue_date');                // Fatura tarihi
            $table->date('due_date');                  // Son ödeme tarihi
            $table->string('source')->default('manual'); // manual, billit, exact vs.
            $table->json('items');                     // Inspection items'dan gelen kalemler
            $table->json('metadata')->nullable();      // API entegrasyonu için ek bilgiler
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('invoices');
    }
};