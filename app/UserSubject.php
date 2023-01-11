<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserSubject extends Model
{
    protected $fillable = [
        'user_id', 'subject_id'
    ];
    protected $table = 'user_subjects';
    protected $primaryKey = 'teaching_id';

    
    public function subjects() 
    {
        return $this->hasMany('App\User');
    }
 
    public static function add($data)
    {
          self::updateOrCreate([
            'user_id' => $data['user_id'], 
            'subject_id' => $data['subject_id'],
        ]);
    }
}
