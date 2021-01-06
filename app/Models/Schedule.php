<?php

namespace App\Models;

use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Schedule extends Model {

 
     protected $casts = [
        'selected_days' => 'array',
        'accept_reject_schedule' => 'array'
    ];

    public function User() {
        return $this->belongsTo('App\User', 'created_by', 'id');
    }
    
  

}
