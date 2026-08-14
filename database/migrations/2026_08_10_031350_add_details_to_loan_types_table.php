<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('loan_types', function (Blueprint $table) {
            $table->string('loan_name');
            $table->integer('duration_months');
            $table->enum('status', ['Active', 'Inactive'])
                  ->default('Active');
        });
    }

    public function down(): void
    {
        Schema::table('loan_types', function (Blueprint $table) {
            $table->dropColumn([
                'loan_name',
                'duration_months',
                'status'
            ]);
        });
    }
};