<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_code',
        'full_name',
        'nic',
        'phone',
        'address',
        'status',
    ];

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }
}