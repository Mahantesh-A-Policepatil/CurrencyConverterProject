<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    //
     protected $table = "currency";
    protected $fillable = [
    		'id',
    		'currency_code',
    		'country',
    		'currency_name',
    		'convertion_rate'
    ];
}
