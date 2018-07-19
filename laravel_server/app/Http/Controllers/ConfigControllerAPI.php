<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Config as ConfigSettings;


class ConfigControllerAPI extends Controller
{
    public function changePlatformEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'host' => 'required',
            'username' => 'required',
            'password' => 'required',
            'port' => 'required|integer|min:1|max:65535',
            'encryption' => 'required'
        ]);

        if(!$validator->fails())
        {
            $email = $request->input('email');
            $platform_email_properties = json_encode(array_except($request->all(), ['email']));

            DB::table('config')->where('id', 1)->update(['platform_email' => $email]);
            DB::table('config')->where('id', 1)->update(['platform_email_properties' => $platform_email_properties]);

            return response()->json(['msg' => 'Email configuration changed!'], 200);
        }
        return response()->json(['msg' => 'An error occured!'], 400);
    }

    public function getPlatformEmailSettings(){

        $settings = DB::table('config')->first();
        return new ConfigSettings($settings);
    }

}