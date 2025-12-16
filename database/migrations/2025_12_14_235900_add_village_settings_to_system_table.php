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
        Schema::create('village_settings', function (Blueprint $table) {
            $table->id();
            $table->string('village_name')->default('Desa Merah Putih');
            $table->string('village_code')->nullable();
            $table->string('village_address')->nullable();
            $table->string('village_phone')->nullable();
            $table->string('village_email')->nullable();
            $table->string('village_head')->nullable();
            $table->string('logo_path')->nullable();
            $table->text('description')->nullable();
            $table->string('province')->nullable();
            $table->string('regency')->nullable();
            $table->string('district')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->json('social_media')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('village_settings');
    }
};
