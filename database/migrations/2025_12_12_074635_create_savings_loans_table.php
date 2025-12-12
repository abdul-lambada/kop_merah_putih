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
        Schema::create('savings_loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['savings', 'loan']);
            $table->string('transaction_number')->unique();
            $table->decimal('amount', 15, 2);
            $table->decimal('interest_rate', 5, 2)->nullable(); // for loans
            $table->integer('tenure_months')->nullable(); // for loans
            $table->decimal('monthly_installment', 15, 2)->nullable(); // for loans
            $table->date('due_date')->nullable(); // for loans
            $table->enum('status', ['pending', 'approved', 'active', 'completed', 'overdue'])->default('pending');
            $table->text('purpose')->nullable(); // for loans
            $table->text('notes')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('savings_loans');
    }
};
