<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('loans', 'customer_id')) {
            Schema::table('loans', function (Blueprint $table) {
                $table->foreignId('customer_id')->nullable();
            });
        }

        if (!Schema::hasColumn('loans', 'loan_type_id')) {
            Schema::table('loans', function (Blueprint $table) {
                $table->unsignedBigInteger('loan_type_id')->nullable();
            });
        }

        if (!Schema::hasColumn('loans', 'loan_number')) {
            Schema::table('loans', function (Blueprint $table) {
                $table->string('loan_number')->nullable()->unique();
            });
        }

        if (!Schema::hasColumn('loans', 'cheque_number')) {
            Schema::table('loans', function (Blueprint $table) {
                $table->string('cheque_number')->nullable()->unique();
            });
        }

        if (!Schema::hasColumn('loans', 'loan_amount')) {
            Schema::table('loans', function (Blueprint $table) {
                $table->decimal('loan_amount', 15, 2)->default(0);
            });
        }

        if (!Schema::hasColumn('loans', 'interest_rate')) {
            Schema::table('loans', function (Blueprint $table) {
                $table->decimal('interest_rate', 5, 2)->default(0);
            });
        }

        if (!Schema::hasColumn('loans', 'duration_months')) {
            Schema::table('loans', function (Blueprint $table) {
                $table->unsignedInteger('duration_months')->default(1);
            });
        }

        if (!Schema::hasColumn('loans', 'monthly_capital')) {
            Schema::table('loans', function (Blueprint $table) {
                $table->decimal('monthly_capital', 15, 2)->default(0);
            });
        }

        if (!Schema::hasColumn('loans', 'monthly_interest')) {
            Schema::table('loans', function (Blueprint $table) {
                $table->decimal('monthly_interest', 15, 2)->default(0);
            });
        }

        if (!Schema::hasColumn('loans', 'monthly_payment')) {
            Schema::table('loans', function (Blueprint $table) {
                $table->decimal('monthly_payment', 15, 2)->default(0);
            });
        }

        if (!Schema::hasColumn('loans', 'remaining_balance')) {
            Schema::table('loans', function (Blueprint $table) {
                $table->decimal('remaining_balance', 15, 2)->default(0);
            });
        }

        if (!Schema::hasColumn('loans', 'loan_date')) {
            Schema::table('loans', function (Blueprint $table) {
                $table->date('loan_date')->nullable();
            });
        }

        if (!Schema::hasColumn('loans', 'status')) {
            Schema::table('loans', function (Blueprint $table) {
                $table->enum('status', [
                    'Pending',
                    'Active',
                    'Completed',
                    'Cancelled',
                ])->default('Active');
            });
        }
    }

    public function down(): void
    {
        $columns = [
            'loan_type_id',
            'loan_number',
            'duration_months',
            'monthly_capital',
            'monthly_interest',
            'monthly_payment',
            'remaining_balance',
        ];

        foreach ($columns as $column) {
            if (Schema::hasColumn('loans', $column)) {
                Schema::table('loans', function (Blueprint $table) use ($column) {
                    $table->dropColumn($column);
                });
            }
        }
    }
};