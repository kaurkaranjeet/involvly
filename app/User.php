<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use URL;
use Hash;
use App\Models\School;
use App\Models\TeachingProgram;

class User extends Authenticatable implements JWTSubject {

    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'profile_image','join_teaching_program'
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

   
    
    public function preferSubjects()
    {
        return $this->belongsTo('App\UserSubject', 'user_id', 'id');
    }

    public function TeachingProgram() {
        return $this->belongsTo(App\Models\TeachingProgram::class, 'foreign_key', 'local_key'); 
      }
    
    public function isRole() {
        return $this->role;
    }

    public function getJWTIdentifier()
        {
            return $this->getKey();
        }
        public function getJWTCustomClaims()
        {
            return [];
        }
    protected static function validateLogin($data) {
        return User::where('email', $data['email'])->with('SchoolDetail')->with('StateDetail')->with('CityDetail')->first();
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
        $this->role_id = $request->input('role_id');
        $this->password = Hash::make($request->input('password'));
        $this->device_token = $request->input('device_token');
        if(!empty($request->class_code)){
           $this->status = 1;
       } else{
           $this->status = 0;
       }  
       
       $this->role_id =$request->role_id;
        $this->exit_groups ='';
       $this->state_id =$request->state_id;
       if(empty($request->city)){
        $this->city =$request->city_id; 
       }else{
       $this->city =$request->city;
       }
       if(isset($request->school_id)){
         $this->school_id =$request->school_id;
     }

      if($request->has('relationship')){
         $this->relationship =$request->relationship;
      }
      if($request->has('type_of_schooling')){
         $this->type_of_schooling =$request->type_of_schooling;
         if($request->type_of_schooling == 'home'){
             $this->status = 1;
         }
      }
     $this->country='United States';
      /* $this->country =$request->country;
       $this->city =$request->city_id;
       if(isset($request->join_community)){
          $this->join_community= $request->join_community;
       }
       $this->type_of_schooling =$request->type_of_schooling;*/
       $this->save();
       if ($this->id > 0) {
        return $this;
    }
}

    protected function fetchUsers()
    {
        return User::select('id as user_id', 'first_name', 'last_name', 'company_name','image', 'status', 'is_blocked', 'email', 'user_type')->with('roles')->get();
    }

    public function documents()
    {
        return $this->hasMany('App\Models\Document', 'user_id', 'id');
    }
    protected function fetchUser($id)
    {
        return User::with('role')->with('StateDetail')->with('CityDetail')->with('SchoolDetail')->with('documents')->where('id', $id)->first();
    }
    protected function FollowedUsers($id){

      return FollowUnfollow::where('followed_user_id', $id)->count();
    }

    protected function FollowingUser($id){
         return FollowUnfollow::where('following_user_id', $id)->count();
    }
    public function Timetables(){
          return $this->hasMany('App\Models\Timetable','teacher_id','id');  
            }
    
    
    /**
     * App\Role relation.
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
   public function role()
{
    return $this->belongsTo('App\Models\Role');
}
    public function SchoolDetail()
    {
        return $this->belongsTo('App\Models\School','school_id');

    }

     public function StateDetail()
    {
        return $this->belongsTo('App\Models\State','state_id');

    }

     public function CityDetail()
    {
        return $this->belongsTo('App\Models\Cities','city');

    }


   protected function getstudents($id){
   return  User::where('school_id',$id)->where('role_id',2)->where('status',1);
   }

    protected function getteachers($id){
   return  User::where('school_id',$id)->where('role_id',4)->where('status',1);
   }

   
}
