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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_number')->unique();
            $table->enum('type', ['income', 'expense', 'transfer']);
            $table->enum('category', ['savings_deposit', 'loan_disbursement', 'loan_payment', 'unit_revenue', 'unit_expense', 'operational_cost', 'other']);
            $table->decimal('amount', 15, 2);
            $table->text('description');
            $table->date('transaction_date');
            $table->foreignId('member_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('business_unit_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('savings_loan_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('recorded_by')->constrained('users');
            $table->string('payment_method')->nullable(); // cash, transfer, etc
            $table->string('reference_number')->nullable(); // bank transfer reference, etc
            $table->text('notes')->nullable();
            $table->json('attachments')->nullable(); // receipts, documents
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
