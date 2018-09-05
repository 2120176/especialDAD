<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Http\Resources\User as UserResource;

define('YOUR_SERVER_URL', 'http://especial.dad');
// Check "oauth_clients" table for next 2 values:
define('CLIENT_ID', '2');
define('CLIENT_SECRET','mG9zwDflI0IMfjRrX16ZaCwW6cQY7igrU2RAkEdx');

class LoginControllerAPI extends Controller
{
	public function login(Request $request)
	{

		$user = User::orWhere('email', $request->email)->orWhere('nickname', $request->email)->first();
        if($user == null){
            return response()->json(['msg'=>'Utilizador/email não existe'], 400);
        }

        if($user->verified == 0){
            return response()->json(['msg'=>'Utilizador não verificado'], 401);
        }

        if($user->blocked == 1) {
            return response()->json(['msg'=>'Utilizador bloqueado'], 402);
        }

		$http = new \GuzzleHttp\Client;
		$response = $http->post(YOUR_SERVER_URL.'/oauth/token', [
		'form_params' => [
		'grant_type' => 'password',
		'client_id' => CLIENT_ID,
		'client_secret' => CLIENT_SECRET,
		'username' => $request->email,
		'password' => $request->password,
		'scope' => ''
		],'exceptions' => false,
		]);
		$errorCode= $response->getStatusCode();
		if ($errorCode=='200') {
			return json_decode((string) $response->getBody(), true);
		} else {
			return response()->json(['msg'=>'User credentials are invalid'], $errorCode);
		}
	}

	public function adminlogin(Request $request)
    {
        /*
    	$user = User::where('email', $request->email)->first();
        if($user == null){
            return response()->json(['msg'=>'Utilizador/email não existe.'], 400);
        }

        if ($user->admin != 1) {
            return response()->json(['msg'=>'Utilizador não autorizado.'], 400);
        }

        if($user->blocked == 1) {
            return response()->json(['msg'=>'Utilizador não activo.'], 400);
        }

*/

        $http = new \GuzzleHttp\Client;
        $response = $http->post(YOUR_SERVER_URL.'/oauth/token', [
        'form_params' => [
        'grant_type' => 'password',
        'client_id' => CLIENT_ID,
        'client_secret' => CLIENT_SECRET,
        'username' => $request->username,
        'password' => $request->password,
        'scope' => ''
        ], 'exceptions' => false,
        ]);
        $errorCode= $response->getStatusCode();
        if ($errorCode=='200') {
            return json_decode((string) $response->getBody(), true);
        } else {
            return response()->json(['msg'=>'User credentials are invalid'], $errorCode);
        }
    }

	public function logout()
	{
		\Auth::guard('api')->user()->token()->revoke();
		\Auth::guard('api')->user()->token()->delete();
		return response()->json(['msg'=>'Token revoked'], 200);
	}
}
