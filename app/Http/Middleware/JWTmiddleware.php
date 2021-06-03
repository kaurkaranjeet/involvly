<?php

    namespace App\Http\Middleware;

    use Closure;
    use JWTAuth;
    use Exception;
    use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
    use App\Models\School;
    use App\Models\Timezone;
    use App\User;

    class JwtMiddleware extends BaseMiddleware
    {

        /**
         * Handle an incoming request.
         *
         * @param  \Illuminate\Http\Request  $request
         * @param  \Closure  $next
         * @return mixed
         */
        public function handle($request, Closure $next)
        {
            try {
                $user = JWTAuth::parseToken()->authenticate();
                if($user){
                $activetoken=$user->ActiveJwttoken;
                $token=JWTAuth::getToken();
                //check token is null or blank
                if($activetoken == null || $activetoken == ''){
                    return response()->json(['message' => 'Token in null','status' => '0'], 401);
                }
                if($activetoken!= $token){
                      return response()->json(['message' => 'Someone else is using your account details. So, you have been logged out.','status' => '0'], 401);
                }
                }else{
                    return response()->json(['message' => 'This account is no longer available.','status' => '0'], 401);
                }
            } catch (Exception $e) {
                if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
                    return response()->json(['message' => 'Someone else is using your account details. So, you have been logged out.','status' => '0'], 401);
                }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
                    //remove the existing deivce token 
                    User::where('id', $user->id)->update(['device_token' => null]);
                    return response()->json(['message' => 'Your token is Expired. Please login again.','status' => '0'], 401);
                }else{
                    return response()->json(['message' => 'Authorization Token not found','status' => '0'],401);
                }
            }
            // if(!empty($user->school_id) || $user->school_id != null){
            //     /*****get timezone data*******/
            //     //check parent has own timezone or not 
                
            //     $schooldata = School::where('id', $user->school_id)->first();
            //     $timezone = Timezone::where('id', $schooldata->timezone_id)->first();
            //     date_default_timezone_set($timezone->timezone_name);
                
            // } 
            if(empty($user->timezone_id) || $user->timezone_id == ''){
                //get school timezone
                $schooldata = School::where('id', $user->school_id)->first();
                $timezone = Timezone::where('id', $schooldata->timezone_id)->first();
                date_default_timezone_set($timezone->timezone_name);
                
            }else{
                //get user timezone
                $timezone = Timezone::where('id', $user->timezone_id)->first();
                date_default_timezone_set($timezone->timezone_name);
            }   
            return $next($request);
        }
    }
    ?>				