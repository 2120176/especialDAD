<?php

use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Jsonable;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('users', 'UserControllerAPI@getUsers');
Route::get('users/emailavailable', 'UserControllerAPI@emailAvailable');
Route::get('users/{id}', 'UserControllerAPI@getUser');
Route::post('users', 'UserControllerAPI@store');
Route::put('users/{id}', 'UserControllerAPI@update');
Route::delete('users/{id}', 'UserControllerAPI@delete');
Route::put('registerLink/{user}', 'UserControllerAPI@changeActivation');
Route::post('updateAvatar', 'UserControllerAPI@updateAvatar');

Route::post('verify', 'UserControllerAPI@verifyUser');
Route::post('forgot-password','UserControllerAPI@forgotPassword');
Route::post('reset-password','UserControllerAPI@resetPassword');


Route::post('changeUserName','UserControllerAPI@changeUserName');
Route::post('changeUserNickname','UserControllerAPI@changeUserNickname');


Route::post('changeUserName','UserControllerAPI@changeUserName');
Route::post('changeUserNickname','UserControllerAPI@changeUserNickname');



// Admin
Route::put('user/blocked/{id}', 'UserControllerAPI@blockUser');
Route::put('user/unblocked/{id}', 'UserControllerAPI@unblockUser');
Route::put('admin/resetPassAdmin/{id}', 'UserControllerAPI@resetPass');
Route::post('changePassword','UserControllerAPI@changePassword');
Route::post('adminLogin', 'LoginControllerAPI@adminlogin');
Route::get('adminEmail/{email}', 'UserControllerAPI@sendMail');
Route::put('admin/reset', 'UserControllerAPI@resetByEmail');
Route::post('changeEmail','UserControllerAPI@updateEmail');


// Platform
Route::get('getPlatformEmailSettings', 'ConfigControllerAPI@getPlatformEmailSettings');
Route::post('changePlatformEmail','ConfigControllerAPI@changePlatformEmail');


// Games
Route::get('games', 'GameControllerAPI@index');
Route::get('games/lobby', 'GameControllerAPI@lobby');
Route::get('games/status/{status}', 'GameControllerAPI@gamesStatus');
Route::get('games/{id}', 'GameControllerAPI@getGame');
Route::post('games', 'GameControllerAPI@store');
Route::patch('games/{id}/join-start', 'GameControllerAPI@joinAndStart');
Route::patch('games/{id}/endgame/{winner}', 'GameControllerAPI@endgame');
Route::get('games/user', 'GameControllerAPI@getUserGames');
Route::get('games/victories', 'GameControllerAPI@getVictoriesDraws');


Route::post('login', 'LoginControllerAPI@login');
Route::middleware('auth:api')->post('logout', 'LoginControllerAPI@logout');
Route::post('register', 'UserControllerAPI@store');


// Decks
Route::post('decks', 'DeckControllerAPI@store');
Route::delete('decks/{id}', 'DeckControllerAPI@delete');
Route::get('decks', 'DeckControllerAPI@getDecks');
Route::put('decks/{id}', 'DeckControllerAPI@update');




//Route::get('defesa', 'UserControllerAPI@getDefesa');
