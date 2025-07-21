<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->string('company');
            $table->string('name');
            $table->string('surname');
            $table->string('email')->unique();
            $table->string('password')->nullable();
            $table->string('phone');
            $table->text('address');
            $table->timestamps();
        });

        // Insert default user
        DB::table('tenants')->insert([
            'company' => 'Company Name',
            'name' => 'Admin',
            'surname' => 'User',
            'email' => 'admin@admin.com',
            'password' => Hash::make('123456'),
            'phone' => '1234567890',
            'address' => 'Default Address',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenants');
    }
};
