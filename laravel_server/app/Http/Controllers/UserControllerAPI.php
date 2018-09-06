<?php

namespace App\Http\Controllers;

use App\Mail\ForgotPassword;
use App\Mail\VerifyMail;
use App\Token;
use App\VerifyUser;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Jsonable;

use App\Http\Resources\User as UserResource;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

use App\User;
use App\StoreUserRequest;
use Hash;
use App\Mail\MailSender;
use Illuminate\Support\Facades\Mail;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Validator;

class UserControllerAPI extends Controller
{
    public function getUsers(Request $request)
    {

        return UserResource::collection(User::all());

    }

    public function getUser($id)
    {
        return new UserResource(User::find($id));
    }

    public function store(Request $request)
    {
       /* $data = [
            'name' => $request->name,
            'nickname' => $request->nickname,
            'email' => $request->email,
            'password' => $request->password,
            'password_confirmation' => $request->password_confirmation,
            'activated' => 1,
        ];
        $user = new User();
        $user->fill($data);
        $user->save();

        if (strcmp($user->password, $user->password_confirmation) !== 0) {
            return response()->json(['msg' => 'Wrong passwords NABO!'], 400);
        }*/

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'nickname' => 'required|unique:users,email',
            'password' => 'required|confirmed|min:6'
        ]);

        if (!$validator->fails()) {

            $user = new User();
            $user->fill($request->all());
            $user->avatar = "null.png";
            $user->verified = 0;
            $user->password = Hash::make($user->password);
            $user->save();


            $verifyUser = new VerifyUser();
            $verifyUser->user_id = $user->id;
            $verifyUser->token = str_random(40);
            $verifyUser->save();


            Mail::to($user->email)->send(new VerifyMail($user, $verifyUser->token));


//            \Mail::to($user)->send(new MailSender('emails.register', $user));
            return response()->json(['message' => 'Registration pending, please check your e-mail'], 200);
            return response()->json(new UserResource($user), 201);
        }
        //return response(['data' => $validator->errors()], 434);
        return response()->json($validator->errors(), 422);

        //return response()->json(new UserResource($user), 201);

    }


    public function verifyUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
        ]);

        if ($request->wantsJson() && !$validator->fails()) {
            $verifyUser = VerifyUser::where('token', $request->token)->first();

            if (empty($verifyUser)) {
                return response()->json(['msg' => 'Token inválido.'], 400);
            }

            $user = User::find($verifyUser->user_id);
            $user->verified = 1;
            $user->save();

            return back()->with(['msg' => 'Utilizador activado.']);
        } else {
            return response()->json(['msg' => 'Request inválido.'], 400);
        }
    }


    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email'
        ]);

        if (!$validator->fails()) {


            $user = User::where('email', $request->input('email'))->first();

            if (!$user) {
                return response()->json(['msg'=>'Check if email is correct!'], 403);
            }

           /* $token = Token::create([
                'user_id' => $user->id,
                'token' => uniqid(),
                'expire_at' => Carbon::now()->addHour(),
            ]);*/


            $token = new Token();
            $token->user_id = $user->id;
            $token->token = str_random(40);
            $token->save();

            \Mail::to($user)->send(new ForgotPassword($token, $token->token));


            return response(['msg' => 'Email sent!'], 200);
        }
        return response(['msg' => $validator->errors()], 433);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
                'name' => 'required',
                'email' => 'required|email|unique:users,email,'.$id,
                'nickname' => 'required|unique:users,email',
            ]);
        $user = User::findOrFail($id);
        $user->update($request->all());
        return new UserResource($user);
    }

    public function blockUser(Request $request, $id) {
        $request->validate([
            'reason_blocked' => 'required'
        ]);
        $user = User::findOrFail($id);
        $user->blocked = 1;
        $user->update($request->all());
        /* Send Email to notify user */
        Mail::to($user)->send(new MailSender('emails.block', $user));

        /* End notification */
        return new UserResource($user);
    }

    public function unblockUser(Request $request, $id) {
        $request->validate([
            'reason_reactivated' => 'required'
        ]);
        $user = User::findOrFail($id);
        $user->blocked = 0;
        $user->update($request->all());

        /* Send Email to notify user */
        Mail::to($user)->send(new MailSender('emails.unblock', $user));


        /* End notification */
        return new UserResource($user);
    }

    public function delete($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        /* Send Email to notify user */

        Mail::to($user)->send(new MailSender('emails.delete', $user));

        /* End notification */
        return response()->json(null, 204);
    }

    public function emailAvailable(Request $request)
    {
        $totalEmail = 1;
        if ($request->has('email') && $request->has('id')) {
            $totalEmail = DB::table('users')->where('email', '=', $request->email)->where('id', '<>', $request->id)->count();
        } else if ($request->has('email')) {
            $totalEmail = DB::table('users')->where('email', '=', $request->email)->count();
        }
        return response()->json($totalEmail == 0);
    }

   /* public function resetPass(Request $request, $id)
    {
        $user = User::findOrFail($id);
        if ($user != null) {
            if(Hash::check($request->old_password, $user->password))
            {
                $user->password = Hash::make($request->password);
                $user->save();
                return response()->json(['message' => 'Current Password Changed Successfully'], 200);
            }


            return response()->json(['message' => 'Current Password is NOT correct'], 422);
        }

        return response()->json(['message' => 'Not allowed'], 401);

    }*/
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|min:3',
            'confirm_password' => 'required|same:password'
        ]);

        if (!$validator->fails()) {

            $token = $request->input('token');
            $databaseToken = DB::table('tokens')
                ->where('token', $token)
                ->first();
            if (!$databaseToken) {
                return response(['data' => 'Bad token'], 403);
            }
            //$databaseToken = Token::where('token', $request->token)->first();



            $user = User::where('id', $databaseToken->user_id)->first();
            $user->password = Hash::make($request->input('password'));
            $user->save();

            //DB::table('tokens')->where('id', $databaseToken->id)->delete();

            return response(['data' => 'Password successfully changed!'], 200);
        }
        return response(['data' => $validator->errors()], 433);

    }

    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'currentPassword' => 'required',
            'newPassword' => 'required|min:3'
        ]);

        if (!$validator->fails()) {

            $user = User::where('id', $request->user_id)->first();


            if (!Hash::check($request->input('currentPassword'), $user->password)) {
                return response()->json(['msg' => 'Wrong password!'], 400);
            }

            $user->password = Hash::make($request->input('newPassword'));
            $user->touch();

            return response()->json(['msg' => 'Password successfully changed!'], 200);

        } else {
            return response()->json(['msg' => 'Invalid request!'], 400);
        }
    }


    public function getAdmin(Request $request)
    {
        $admin = User::where('email', $request->email)->first();
        if ($admin != null && $admin->admin == 1) {
            return response()->json(['admin' => '1'], 200);
        }
        return response()->json(['admin' => '0'], 401);

    }

    /*public function sendMail(Request $request) {
        $admin = User::where('email', $request->email)->first();
        if ($admin != null && $admin->admin == 1) {
            Mail::to($admin)->send(new MailSender('emails.adminReset', $admin));
            return response()->json(['admin' => '1'], 200);
        }
        return response()->json(['admin' => '0'], 401);
    }*/


    public function resetByEmail()
    {
        $admin = User::where('email', 'admin@mail.dad')->first();
        $admin->password = Hash::make('secret');
        $admin->save();

        Mail::to($admin)->send(new MailSender('emails.newPassword', $admin));


    }

    public function registerLink($user)
    {
        $user = User::findOrFail($user->id);
        $user->activated = 1;
        $user->save();
    }


    public function updateAvatar(Request $request)
    {

        $request->validate([
            'avatar' => 'required',
        ]);

        $user = User::where('id', $request->user_id)->first();


            $base64avatar = $request->get('avatar');

            if($user->avatar == 'null.png'){
                $user->avatar = $user->id . '.png';
                $user->touch();
            }
            $avatarImg = Image::make($base64avatar);
            $avatarImg->resize(200, 200)->save('img/avatar/' . $user->id . '.png');

        $email_configuration_data = (array)json_decode(DB::table('config')->first()->platform_email_properties);
        $string = str_replace('\"', '"', $email_configuration_data['port']);
        $var1 = (int)$email_configuration_data['port'];
        dd($var1);
        //dd($var1);
       // dd(trim($email_configuration_data['port'], '\"') );
        //dd(str_replace('"', "", $email_configuration_data['password']));
        //dd(str_replace("\"","",$email_configuration_data['encryption']));
        //dd(stripslashes($email_configuration_data['encryption']));

            return response()->json(['msg' => 'Avatar changed!'],200);

    }

    public function updateEmail(Request $request)
    {
        //return response()->json($request->user_id,200);

        $validator = Validator::make($request->all(), [
            'email' => 'email|required'
        ]);

        if (!$validator->fails()) {
            $verifyEmail = User::where('email', $request->input('email'))->count();

            if ($verifyEmail > 0) {
                return response()->json(['msg' => 'Email already being used!'], 400);
            }

            $user = User::where('id', $request->user_id)->first();

            $user->email = $request->input('email');
            $user->touch();
            return response()->json(['msg' => 'Email changed!'], 200);
        } else {
            return response()->json(['msg' => 'Invalid request!'], 400);
        }
    }

    public function changeUserNickname(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nickname' => 'required'
        ]);

        if (!$validator->fails()) {

            $verifyNickname = User::where('nickname', $request->input('nickname'))->count();

            if ($verifyNickname > 0) {
                return response()->json(['msg' => 'Nickname already being used!'], 400);
            }

            $user = User::where('id', $request->user_id)->first();
            $user->nickname = $request->input('nickname');
            $user->touch();
            return response()->json(['msg' => 'Nickname changed!'], 200);
        } else {
            return response()->json(['msg' => 'Invalid request!'], 400);
        }
    }

    public function changeUserName(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required'
        ]);

        if (!$validator->fails()) {

            $user = User::where('id', $request->user_id)->first();

            $user->name = $request->input('name');
            $user->touch();
            return response()->json(['msg' => 'Name changed!'], 200);
        } else {
            return response()->json(['msg' => 'Invalid request!'], 400);
        }

    }


    public function getDefesa () {
        $blockeds = User::where('total_games_played','<>' , 0)->count();


        return response()->json(['users' => $blockeds], 200);

        //return new UserResource(User::where('blocked', 1)->count());
    }
  }
