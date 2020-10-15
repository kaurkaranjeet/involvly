<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class ParentChildrens extends Model {
   
   protected $table='parent_childrens';

 public function ParentDetails(){
   return $this->belongsTo('App\User','parent_id','id');
 }

 public function ChildDetails(){
   return $this->belongsTo('App\User','children','id');
 }

    
}
