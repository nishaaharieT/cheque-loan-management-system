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
    Schema::create('payments', function (Blueprint $table) {
        $table->id();

        $table->foreignId('loan_id')->constrained()->restrictOnDelete();

        $table->string('receipt_number')->unique();

        $table->date('payment_date');

        $table->decimal('capital_paid', 15, 2);
        $table->decimal('interest_paid', 15, 2);
        $table->decimal('total_paid', 15, 2);

        $table->decimal('remaining_balance', 15, 2);

        $table->text('remarks')->nullable();

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
