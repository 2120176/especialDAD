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

Route::middleware('auth:api')->get('users', 'UserControllerAPI@getUsers');
Route::middleware('auth:api')->get('users/emailavailable', 'UserControllerAPI@emailAvailable');
Route::middleware('auth:api')->get('users/{id}', 'UserControllerAPI@getUser');
Route::middleware('auth:api')->post('users', 'UserControllerAPI@store');
Route::middleware('auth:api')->put('users/{id}', 'UserControllerAPI@update');
Route::middleware('auth:api')->delete('users/{id}', 'UserControllerAPI@delete');
Route::put('registerLink/{user}', 'UserControllerAPI@changeActivation');
Route::middleware('auth:api')->post('updateAvatar', 'UserControllerAPI@updateAvatar');

Route::post('verify', 'UserControllerAPI@verifyUser');
Route::post('forgot-password','UserControllerAPI@forgotPassword');
Route::middleware('auth:api')->post('reset-password','UserControllerAPI@resetPassword');


Route::middleware('auth:api')->post('changeUserName','UserControllerAPI@changeUserName');
Route::middleware('auth:api')->post('changeUserNickname','UserControllerAPI@changeUserNickname');


Route::middleware('auth:api')->post('changeUserName','UserControllerAPI@changeUserName');
Route::middleware('auth:api')->post('changeUserNickname','UserControllerAPI@changeUserNickname');



// Admin
Route::middleware('auth:api')->put('user/blocked/{id}', 'UserControllerAPI@blockUser');
Route::middleware('auth:api')->put('user/unblocked/{id}', 'UserControllerAPI@unblockUser');
Route::middleware('auth:api')->put('admin/resetPassAdmin/{id}', 'UserControllerAPI@resetPass');
Route::middleware('auth:api')->post('changePassword','UserControllerAPI@changePassword');
Route::middleware('auth:api')->post('adminLogin', 'LoginControllerAPI@adminlogin');
Route::middleware('auth:api')->get('adminEmail/{email}', 'UserControllerAPI@sendMail');
Route::middleware('auth:api')->put('admin/reset', 'UserControllerAPI@resetByEmail');
Route::middleware('auth:api')->post('changeEmail','UserControllerAPI@updateEmail');


// Platform
Route::middleware('auth:api')->get('getPlatformEmailSettings', 'ConfigControllerAPI@getPlatformEmailSettings');
Route::middleware('auth:api')->post('changePlatformEmail','ConfigControllerAPI@changePlatformEmail');


// Games
Route::middleware('auth:api')->get('games', 'GameControllerAPI@index');
Route::middleware('auth:api')->get('games/lobby', 'GameControllerAPI@lobby');
Route::middleware('auth:api')->get('games/status/{status}', 'GameControllerAPI@gamesStatus');
Route::middleware('auth:api')->get('games/{id}', 'GameControllerAPI@getGame');
Route::middleware('auth:api')->post('games', 'GameControllerAPI@store');
Route::middleware('auth:api')->patch('games/{id}/join-start', 'GameControllerAPI@joinAndStart');
Route::middleware('auth:api')->patch('games/{id}/endgame/{winner}', 'GameControllerAPI@endgame');
Route::middleware('auth:api')->get('games/user', 'GameControllerAPI@getUserGames');
Route::middleware('auth:api')->get('games/victories', 'GameControllerAPI@getVictoriesDraws');


Route::post('login', 'LoginControllerAPI@login');
Route::middleware('auth:api')->post('logout', 'LoginControllerAPI@logout');
Route::post('register', 'UserControllerAPI@store');


// Decks
/*Route::post('decks', 'DeckControllerAPI@store');
Route::delete('decks/{id}', 'DeckControllerAPI@delete');
Route::get('decks', 'DeckControllerAPI@getDecks');
Route::put('decks/{id}', 'DeckControllerAPI@update');
Route::get('activeDeck', 'DeckControllerAPI@getActiveDeck');*/

//Cards and Decks
Route::middleware('auth:api')->get('cards', 'CardControllerAPI@getCards');
Route::middleware('auth:api')->delete('cards/{id}', 'CardControllerAPI@deleteCard');
Route::middleware('auth:api')->post('cards', 'CardControllerAPI@store');

Route::middleware('auth:api')->get('deck','CardControllerAPI@getDecks');
Route::middleware('auth:api')->post('deck','CardControllerAPI@createDeck');
Route::middleware('auth:api')->delete('deck/{id}','CardControllerAPI@deleteDeck');



//Route::get('defesa', 'UserControllerAPI@getDefesa');
