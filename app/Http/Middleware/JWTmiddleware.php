<?php

    namespace App\Http\Middleware;

    use Closure;
    use JWTAuth;
    use Exception;
    use User;

    use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

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

                //check token is existing or or not
                if(User::where('ActiveJwttoken',$activetoken)->exists()){
                    
                }else{
                    return response()->json(['message' => 'Token is not exist','status' => '0'], 401);
                }

                if($activetoken!= $token){
                      return response()->json(['message' => 'Token is Invalid','status' => '0'], 401);
                }
            }
            } catch (Exception $e) {
                if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
                    return response()->json(['message' => 'Token is Invalid','status' => '0'], 401);
                }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
                    return response()->json(['message' => 'Token is Expired','status' => '0'], 401);
                }else{
                    return response()->json(['message' => 'Authorization Token not found','status' => '0'],401);
                }
            }
            return $next($request);
        }
    }
    ?>