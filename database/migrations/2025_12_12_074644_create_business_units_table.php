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
        Schema::create('business_units', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['sembako', 'apotek', 'klinik', 'logistik']);
            $table->text('description')->nullable();
            $table->string('address');
            $table->string('phone')->nullable();
            $table->string('manager_name');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->decimal('initial_capital', 15, 2)->default(0);
            $table->decimal('current_balance', 15, 2)->default(0);
            $table->json('operating_hours')->nullable(); // JSON for opening/closing times
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('business_units');
    }
};
