<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loans', function (Blueprint $table) {

            $table->id();

            // Customer who receives the loan
            $table->foreignId('customer_id')
                ->constrained('customers')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            // Loan details
            $table->string('loan_code')->unique();

            $table->decimal('loan_amount', 15, 2);

            $table->enum('loan_period', [
                '1 Month',
                '4 Months',
                '6 Months',
                '1 Year'
            ]);

            // Interest rate stored for each loan
            $table->decimal('interest_rate', 5, 2);

            // Calculated interest and total payable
            $table->decimal('total_interest', 15, 2)->default(0);
            $table->decimal('total_payable', 15, 2)->default(0);

            // Cheque information
            $table->string('cheque_number')->unique();
            $table->date('cheque_date');

            // Loan dates
            $table->date('loan_date');
            $table->date('due_date');

            // Loan status
            $table->enum('status', [
                'Pending',
                'Active',
                'Completed',
                'Cancelled'
            ])->default('Pending');

            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};