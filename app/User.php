<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use URL;
use App\FollowUnfollow;

class User extends Authenticatable implements JWTSubject {

    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'image',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function isRole() {
        return $this->role;
    }

    public function getJWTIdentifier() {
        return $this->getKey();
    }

    public function getJWTCustomClaims() {
        return [];
    }

    protected function validateLogin($data) {
        return User::where('email', $data['email'])->first();
    }
    protected function validateautheticationkey($data) {
        return User::where('authenticiation_key', $data['authenticiation_key'])->first();
    }


    /**
     * store user data 
     * @param type $request
     * @return $this
     */
    public function store($request) {
        $this->name = $request->input('first_name').' '.$request->input('last_name');
        $this->email = $request->input('email');
        $this->last_name = $request->input('last_name');
          $this->first_name = $request->input('first_name');
        $this->password = $request->input('password');
        $this->device_token = $request->input('device_token');
        if(!empty($request->class_code)){
         $this->status = '1';
     } else{
         $this->status = '0';
     }  
     $this->role_id =$request->role_id;
     $this->state_id =$request->state_id;
      $this->school_id =$request->school_id;
     $this->country =$request->country;
     $this->city =$request->city;
     $this->type_of_schooling =$request->type_of_schooling;
     $this->save();
     if ($this->id > 0) {
        return $this;
    }
}

    protected function fetchUsers()
    {
        return User::select('id as user_id', 'first_name', 'last_name', 'company_name','image', 'status', 'is_blocked', 'email', 'user_type')->with('roles')->get();
    }
    protected function fetchUser($id)
    {
        return User::select('name','username', 'id', 'email','status','image','authenticiation_key','type','bio','insta_key')->with('roles')->where('id', $id)->first();
    }
    protected function FollowedUsers($id){

      return FollowUnfollow::where('followed_user_id', $id)->count();
    }

    protected function FollowingUser($id){
         return FollowUnfollow::where('following_user_id', $id)->count();
    }

    
    
    /**
     * App\Role relation.
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    


}
