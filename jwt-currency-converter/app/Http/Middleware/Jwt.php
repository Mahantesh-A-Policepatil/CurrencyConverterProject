<?php
namespace App\Http\Middleware;
use Closure;
use JWTAuth;

class Jwt
{

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request            
     * @param \Closure $next            
     * @return mixed
     */
    public function handle ($request, Closure $next)
    {
        try {
            
            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json([
                        'user_not_found'
                ], 404);
            }
        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            
            return response()->json([
                    'token_expired'
            ], $e->getStatusCode());
            
        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            
            return response()->json([
                    'token_invalid'
            ], $e->getStatusCode());
            
        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
            
            return response()->json([
                    'token_absent'
            ], $e->getStatusCode());
            
        } catch (\Exception $e) {
            
            if($e->getStatusCode()=="401"){
                
                return response()->json([
                        'error' => "Unauthorised Access"
                ],$e->getStatusCode());
                
            }else{
                
                return response()->json([
                        'error' => $e->getMessage()
                ],$e->getStatusCode());
                
            }
            
        }
        return $next($request);
    }
}
