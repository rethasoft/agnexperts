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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->morphs('documentable'); // Creates documentable_id and documentable_type
            $table->string('name');
            $table->string('file_path');
            $table->string('file_type');
            $table->string('document_type'); // e.g., contract, certificate, ID, etc.
            $table->text('description')->nullable();
            $table->date('issue_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->string('verified_by')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->json('meta')->nullable(); // Meta veriler için JSON alanı
            $table->timestamps();
            $table->softDeletes(); // For document versioning and recovery
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_documents');
    }
};
