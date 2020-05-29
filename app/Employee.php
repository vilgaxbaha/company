<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
     protected $table = "employees";

    public function payment_reports()
    {
        return $this->hasMany('App\PaymentReport');
    }
}
