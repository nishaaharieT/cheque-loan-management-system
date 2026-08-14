<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'loan_type_id',
        'loan_number',
        'cheque_number',
        'loan_amount',
        'interest_rate',
        'duration_months',
        'monthly_capital',
        'monthly_interest',
        'monthly_payment',
        'remaining_balance',
        'loan_date',
        'status',
    ];

    protected $casts = [
        'loan_amount' => 'decimal:2',
        'interest_rate' => 'decimal:2',
        'monthly_capital' => 'decimal:2',
        'monthly_interest' => 'decimal:2',
        'monthly_payment' => 'decimal:2',
        'remaining_balance' => 'decimal:2',
        'loan_date' => 'date',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function loanType()
    {
        return $this->belongsTo(LoanType::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}