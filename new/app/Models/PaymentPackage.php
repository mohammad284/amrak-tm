<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentPackage extends Model
{
    protected $fillable = ['amount','payment_status_id','user','payment_method','email'];
}
