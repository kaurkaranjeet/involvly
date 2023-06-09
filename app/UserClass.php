<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserClass extends Model
{
    protected $fillable = [
        'user_id',
        'class_id'
    ];
    protected $table = 'user_class';


    protected $primaryKey = 'user_class_id';


    public function subjects()
    {
        return $this->hasMany('App\User');
    }

    public static function add($data)
    {
        self::updateOrCreate(
            [
                'user_id' => $data['user_id'],
                'class_id' => $data['class_id'],
            ],
            [
                'user_id' => $data['user_id'],
                'class_id' => $data['class_id'],
            ]
        );
    }
}