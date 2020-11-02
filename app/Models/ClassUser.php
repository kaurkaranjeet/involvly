<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassUser extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id','class_id'];
    protected $table='user_class_code';

    /**
     * App\User relationship
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    
}