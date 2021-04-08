<?php

    namespace App\Http\Middleware;

    use Closure;
    use JWTAuth;
    use Exception;
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
                if($activetoken!= $token){
                      return response()->json(['message' => 'Token is Invalid','status' => '0']);
                }
            }
            } catch (Exception $e) {
                if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
                    return response()->json(['message' => 'Token is Invalid','status' => '0']);
                }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
                    return response()->json(['message' => 'Token is Expired','status' => '0']);
                }else{
                    return response()->json(['message' => 'Authorization Token not found','status' => '0']);
                }
            }
            return $next($request);
        }
    }
    ?>