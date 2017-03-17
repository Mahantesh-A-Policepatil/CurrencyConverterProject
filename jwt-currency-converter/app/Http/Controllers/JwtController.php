<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;






use App\Http\Requests;
use App\Http\Controllers\Controller;
use JWTAuth;
use Tymon\JWTAuthExceptions\JWTException;


class JwtController extends Controller
{
    //

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
}
