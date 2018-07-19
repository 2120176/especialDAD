<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Jsonable;

use App\Http\Resources\User as UserResource;
use Illuminate\Support\Facades\DB;

use App\User;
use App\StoreUserRequest;
use Hash;
use App\Mail\MailSender;
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
            'nickname' => 'required',
            'password' => 'required|confirmed|min:6'
        ]);

        if (!$validator->fails()) {

            $user = new User();
            $user->fill($request->all());
            $user->avatar = "null.png";
            $user->password = Hash::make($user->password);
            $user->save();
            \Mail::to($user)->send(new MailSender('emails.register', $user));
            return response()->json(['message' => 'registration success'], 200);
            return response()->json(new UserResource($user), 201);
        }
        //return response(['data' => $validator->errors()], 434);
        return response()->json($validator->errors(), 422);



        //return response()->json(new UserResource($user), 201);

    }

    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'email|required'
        ]);

        if (!$validator->fails()) {


            $user = User::where('email', $request->input('email'))->first();

            if (!$user) {
                return response(['data' => 'Check if email is correct'], 403);
            }

            $token = Token::create([
                'user_id' => $user->id,
                'token' => uniqid(),
                'expire_at' => Carbon::now()->addHour(),
            ]);


            Mail::to($user)->send(new ForgotPassword($token, $request));


            return response(['msg' => 'Email sent!'], 200);
        }
        return response(['data' => $validator->errors()], 433);
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
        \Mail::to($user)->send(new MailSender('emails.block', $user));
        
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
        \Mail::to($user)->send(new MailSender('emails.unblock', $user));
        

        /* End notification */
        return new UserResource($user);
    }

    public function delete($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        /* Send Email to notify user */
        
        \Mail::to($user)->send(new MailSender('emails.delete', $user));
        
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

    public function resetPass(Request $request, $id)
    {
        $user = User::findOrFail($id);
        if ($user != null) {
            if(Hash::check($request->password, $user->password))
            {
                $user->password = Hash::make($request->password); 
                $user->save();         
                return response()->json(['message' => 'Current Password Changed Successfully'], 200);
            }

            
            return response()->json(['message' => 'Current Password is NOT correct'], 422);
        }
        
        return response()->json(['message' => 'Not allowed'], 401);

    }

    public function getAdmin(Request $request)
    {
        $admin = User::where('email', $request->email)->first();
        if ($admin != null && $admin->admin == 1) {
            return response()->json(['admin' => '1'], 200);
        }
        return response()->json(['admin' => '0'], 401);
        
    }

    public function sendMail(Request $request) {
        $admin = User::where('email', $request->email)->first();
        if ($admin != null && $admin->admin == 1) {
            \Mail::to($admin)->send(new MailSender('emails.adminReset', $admin));
            return response()->json(['admin' => '1'], 200);
        }
        return response()->json(['admin' => '0'], 401);
    }


    public function resetByEmail()
    {
        $admin = User::where('email', 'admin@mail.dad')->first();
        $admin->password = Hash::make('secret'); 
        $admin->save();

        \Mail::to($admin)->send(new MailSender('emails.newPassword', $admin));

        
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

            return response()->json(['msg' => 'Avatar changed!'],200);

    }
    

    public function getDefesa () {
        $blockeds = User::where('total_games_played','<>' , 0)->count();


        return response()->json(['users' => $blockeds], 200);

        //return new UserResource(User::where('blocked', 1)->count());
    }
  }
