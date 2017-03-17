<?php
use Illuminate\Http\Request;



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
/*
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

 header('Access-Control-Allow-Origin: *'); 
 header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
 header('Access-Control-Allow-Headers: X-Requested-With, Content-Type, X-Auth-Token, Origin, Authorization');


 Route::group(['prefix'=>'v1'],function(){
       
        // login API
        Route::post('login', 'JwtController@login');
       
  });
  */

 header('Access-Control-Allow-Origin: *'); 
 header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
 header('Access-Control-Allow-Headers: X-Requested-With, Content-Type, X-Auth-Token, Origin, Authorization');

 Route::post('login', 'Auth\LoginController@login');
 Route::get('register_user', 'Auth\LoginController@register');


Route::group([
        'prefix' => 'v1'
       
], 
function ()
{
  
    Route::post('create_currency','CurrencyController@store');
    Route::get('show_currency_list','CurrencyController@index');
    Route::get('convert_currency/amount/{amount}/from/{from}/to/{to}', 'CurrencyController@convertCurrency');
    // Logout an user
    Route::post('logout','Auth\LoginController@logout');
});


