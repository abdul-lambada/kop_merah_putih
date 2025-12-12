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
        Schema::create('financial_reports', function (Blueprint $table) {
            $table->id();
            $table->string('report_number')->unique();
            $table->enum('type', ['monthly', 'quarterly', 'annual', 'custom']);
            $table->string('title');
            $table->date('period_start');
            $table->date('period_end');
            $table->decimal('total_income', 15, 2)->default(0);
            $table->decimal('total_expense', 15, 2)->default(0);
            $table->decimal('net_profit', 15, 2)->default(0);
            $table->decimal('total_savings', 15, 2)->default(0);
            $table->decimal('total_loans', 15, 2)->default(0);
            $table->decimal('loan_portfolio', 15, 2)->default(0);
            $table->integer('active_members_count')->default(0);
            $table->json('unit_performance')->nullable(); // business units performance data
            $table->json('charts_data')->nullable(); // data for charts visualization
            $table->text('summary')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('generated_by')->constrained('users');
            $table->timestamp('generated_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('financial_reports');
    }
};
