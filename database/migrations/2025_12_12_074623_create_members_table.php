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
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->string('member_number')->unique();
            $table->string('nik')->unique();
            $table->string('full_name');
            $table->string('email')->unique()->nullable();
            $table->string('phone');
            $table->text('address');
            $table->enum('business_sector', ['pertanian', 'peternakan', 'perikanan', 'umkm']);
            $table->enum('experience', ['baru', '2-5_tahun', '5+_tahun']);
            $table->date('join_date');
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
            $table->decimal('savings_balance', 15, 2)->default(0);
            $table->decimal('loan_limit', 15, 2)->default(0);
            $table->json('verification_data')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
