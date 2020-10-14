<?php

 namespace App\Http\Controllers;
    use App\User;
    use App\Models\Role;
    use DB;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Hash;
    use Illuminate\Support\Facades\Validator;
    use JWTAuth;
    use Tymon\JWTAuth\Exceptions\JWTException;

    class UserController extends Controller
    {
        public function login(Request $request)
        {
            $credentials = $request->only('email', 'password');

            try {
                if (! $accessToken = JWTAuth::attempt($credentials)) {
                    return response()->json(['error' => 'invalid_credentials'], 400);
                }
            } catch (JWTException $e) {
                return response()->json(['error' => 'could_not_create_token'], 500);
            }

          $userData = User::where('email', '=', $request->input('email'))->whereIn('role_id', [5,1])->select('name AS displayName','email','id AS uid')->first();
            return response()->json(compact('accessToken','userData'));
        }
        
        // Register API
 public function register(Request $request)
        {
          $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'city_id' => 'required',
            'state_id' => 'required',
            'school_id' => 'required',
            'country' => 'required'
          ]);

            if($validator->fails()){
                   return response()->json($validator->errors()->toJson(), 400);
            }

            $user =new  User;
            $user->name=$request->name;
            $user->email=$request->email;
            $user->password=$request->password;
            $user->city_id=$request->city_id;
            $user->state_id=$request->state_id;
            $user->school_id=$request->school_id;
            $user->country=$request->country;
            $user->password=Hash::make($request->password;
            $user->role_id=5;

            $token = JWTAuth::fromUser($user);

            return response()->json(compact('user','token'),200);
        }

        public function gettotalStatistic($id)
        {
         $students=User::getstudents($id);
         $teachers=User::getteachers($id);
         return response()->json(compact('students','teachers'), 200);
       }

        public function getAuthenticatedUser()
            {
                    try {

                            if (! $user = JWTAuth::parseToken()->authenticate()) {
                                    return response()->json(['user_not_found'], 404);
                            }

                    } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

                            return response()->json(['token_expired'], $e->getStatusCode());

                    } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

                            return response()->json(['token_invalid'], $e->getStatusCode());

                    } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

                            return response()->json(['token_absent'], $e->getStatusCode());

                    }

                    return response()->json(compact('user'), 200);
            }


            public function manageUsers(Request $request)
            {
              DB::enableQueryLog(); 
              if($request->type=='teacher'){
                 $users = User::where('role_id', 4)->where('status', 1)->with('role')->get();
              }
              else if($request->type=='students'){
                 $users = User::where('role_id', 2)->where('status', 1)->get();
              }
              else{
                 $users = User::where('role_id', 3)->where('status', 1)->get();
              }
            //  print_r(DB::getQueryLog());die;
       
                if (isset($users) && count($users) > 0) {
                    return response()->json(compact('users'), 200);
              } else {
                  return response()->json(['error' => 'true','users' =>[],'message' => 'No record found'], 200);
              }
          }

          public function fetchUser($id)
  {
      $data = User::fetchUser($id);
    if (isset($data)) {
         return response()->json(compact('data'), 200);
    
    } else {
       return response()->json(['message' => 'No record found'], 200);
    }
  }

  public function UpdateProfile(Request $request)
  {
      User::where('id',$request->id)->update(['name'=>$request->name,'email'=>$request->email,'status'=>$request->status]);
       $RoleObj = new Role;
       $role_id= $RoleObj->Role($request->role);
       RoleUser::updateOrCreate(['role_id'=>$role_id,'user_id'=>$request->user_id]);
      $data = User::fetchUser($request->id);
      if (isset($data)) {
        return response()->json(compact('data'), 200);
    } else {
     return response()->json(['message' => 'No record found'], 200);
 }
}
public function  RemoveUser($id){
  $data= User::where('id',$id)->delete();
  return response()->json(compact('data'), 200);
}
  }
    ?>