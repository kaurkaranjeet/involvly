<?php

namespace App\Models;

use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Timetable extends Model {

    protected $table = 'timetables';
    protected $fillable = ['teacher_id', 'date', 'selected_days', 'timetable_doc', 'school_id'];
    protected $casts = [
        'selected_days' => 'array',
        'date' => 'datetime:d/m/Y',
    ];

    public function User() {
        return $this->belongsTo('App\User', 'teacher_id', 'id');
    }

}
