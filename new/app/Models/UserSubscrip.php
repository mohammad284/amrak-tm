<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Package;
class UserSubscrip extends Model
{
    protected $fillable = ['package','user','expired_date','email'];

    public function package() {
        return $this->belongTo('App\Models\Package');
    }
}