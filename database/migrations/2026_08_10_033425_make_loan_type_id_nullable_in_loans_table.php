<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('loans', 'loan_type_id')) {
            Schema::table('loans', function (Blueprint $table) {
                $table->unsignedBigInteger('loan_type_id')
                    ->nullable()
                    ->change();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('loans', 'loan_type_id')) {
            Schema::table('loans', function (Blueprint $table) {
                $table->unsignedBigInteger('loan_type_id')
                    ->nullable(false)
                    ->change();
            });
        }
    }
};