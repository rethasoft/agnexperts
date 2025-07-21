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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->nullableMorphs('eventable');
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->string('title')->nullable();  // Ekledik
            $table->text('description')->nullable();  // Ekledik
            $table->datetime('start_date');
            $table->datetime('end_date');
            $table->string('type');
            $table->string('status')->default('scheduled');
            $table->boolean('is_available')->default(false);
            $table->boolean('is_all_day')->default(false);  // Ekledik (tüm gün etkinliği mi?)
            $table->json('meta')->nullable();  // Ekledik (ek bilgiler için)

            // Yeni eklenen alanlar
            $table->string('cancellation_reason')->nullable();
            $table->foreignId('cancelled_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();  // Ekledik

            // Indexes
            $table->index(['start_date', 'end_date']);
            $table->index(['employee_id', 'start_date']);
            $table->index('type');
            $table->index('status'); // Status için index ekledik
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
