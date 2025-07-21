<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('logs', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // email, payment, status_change vb.
            $table->string('event'); // sent, failed, created vb.
            $table->nullableMorphs('loggable'); // ilişkili model (Invoice, Order vb.)
            $table->json('data')->nullable(); // ekstra veriler
            $table->string('status')->nullable(); // başarılı, başarısız, beklemede vb.
            $table->text('message')->nullable(); // hata mesajı veya açıklama
            $table->timestamps();
            
            // Filtreleme için indexler
            $table->index(['type', 'event']);
            $table->index('created_at');
        });
    }
};