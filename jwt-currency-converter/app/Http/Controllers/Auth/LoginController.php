<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use JWTAuth;
use Tymon\JWTAuthExceptions\JWTException;
use Illuminate\Support\Facades\Redirect;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('guest', ['except' => 'logout']);
    }
    /*
    public function login(Request $request)
    {
        $this->validateLogin($request);

        if ($token = $this->guard()->attempt($credentials)) {
            return $this->sendLoginResponse($request, $token);
        }
    }
    */

    public function login(Request $request){
        
        // grab credentials from the request
       
        $credentials = $request->only('email', 'password');
        try {
            // attempt to verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['error' => 'could_not_create_token'], 500);
        }
        $result = compact('token');
        $result['data']=JWTAuth::toUser($result['token'])->toArray();
        
        
            
        // all good so return the token
        return response()->json($result);
    }
    // Register will simply redirect to https://secure.php.net/
    public function register(){
        return Redirect::to('https://secure.php.net/');
    }

    public function logout(Request $request){
        
        try{
            /*
            $this->guard()->logout();
            $request->session()->flush();
            $request->session()->regenerate();
            */
            $user = JWTAuth::parseToken()->authenticate();
            JWTAuth::parseToken()->authenticate();
            $token = JWTAuth::getToken();
            JWTAuth::setToken($token)->invalidate();

          
            
            return response()->json([
                    'status'=>'201',
                    'description' => "User logged out and JWToken is invalidated"
            ]);
        }catch(Exception $e){
            
            return response()->json([
                    'status'=>'400',
                    'description' => $e->getMessage()
            ]);
            
        }catch(TokenBlacklistedException $e){
            
            return response()->json([
                    'status'=>'400',
                    'description' => $e->getMessage()
            ]);
        }
    }
   
}
