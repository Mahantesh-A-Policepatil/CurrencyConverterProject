<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;



use App\Http\Requests;
use Auth;
use App\Currency;
use App\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Validator;

class CurrencyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $currencyDetails = Currency::all();
            return response()->json($currencyDetails);
         } catch (JWTException $e) {
            return response()->json([
                    'status'=>'400',
                    'description' => $e->getMessage()
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }
    /**
     * Login
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request){
        //echo "Hi CurrencyController"; exit;
        
        // grab credentials from the request
        $rules = [
            'email' => 'required|email',
            'password' => 'required'
        ];
    
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json(
                    [
                    'status' => '400',
                    'error' => $errors->first()
                    ]);
        } else {
       
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
     /**
     * Register
     *
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request){
        
        try{
            
            $this->guard()->logout();
            $request->session()->flush();
            $request->session()->regenerate();

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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $user = JWTAuth::parseToken()->authenticate();
         $rules = [
            'currency_code' => 'required',
            'country' => 'required',
            'currency_name' => 'required',
            'convertion_rate' => 'required',
        ];
    
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json(
                    [
                    'status' => '400',
                    'error' => $errors->first()
                    ]);
        } else {
             try{
                $curr=Currency::create($request->all());
                return response()->json([
                    'status'=>'200',
                    'message' => 'New Currency has been added successfully',
                    'user_id' => Auth::guard('api')->id()
                ]);
            } catch (\Exception $e) {
                return response()->json(
                    [
                        'status' => "400",
                        'description' => $e->getMessage()
                    ]);
            }
        }
    }

    function convertCurrency($amount, $from, $to){
         try{
            $url  = "https://www.google.com/finance/converter?a=$amount&from=$from&to=$to";
            $data = file_get_contents($url);
            preg_match("/<span class=bld>(.*)<\/span>/",$data, $converted);
            $converted = preg_replace("/[^0-9.]/", "", $converted[1]);
           // return round($converted, 3);
            return response()->json([
                   
                    'message' => 'Currency Converted Succesfully',
                    'result' => round($converted, 3)
                ]);
         } catch (\Exception $e) {
            return response()->json(
                [
                    'status' => "400",
                    'description' => $e->getMessage()
                ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
