<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $table = "customers";

    public $timestamps = false;

    protected $fillable = ["customer_id", "full_name", "date_of_birth", "loan_balance","used_amount", "installment_plan"];
}
