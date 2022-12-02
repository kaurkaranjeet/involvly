<?php

namespace App\Models;

use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Cities extends Model
{ 
    protected $table = 'cities';
    protected $fillable = [
        'city',
        'county',
        'latitude',
        'longitude',
        'state_id',
    ];
}
    