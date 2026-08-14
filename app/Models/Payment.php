<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'loan_id',
        'receipt_number',
        'payment_date',
        'capital_paid',
        'interest_paid',
        'total_paid',
        'remaining_balance',
        'remarks',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'capital_paid' => 'decimal:2',
        'interest_paid' => 'decimal:2',
        'total_paid' => 'decimal:2',
        'remaining_balance' => 'decimal:2',
    ];

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }
}