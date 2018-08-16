<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
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
            'password' => 'required',
            'port' => 'required|integer|min:1|max:65535',
            'encryption' => 'required'
        ]);

        if(!$validator->fails())
        {
            $email = $request->input('email');
            $host = $request->input('host');
            $port = $request->input('port');
            $password = $request->input('password');
            $encryption= $request->input('encryption');
            $platform_email_properties = json_encode(array_except($request->all(), ['email']));

            config([
                'mail.host' => $host,
                'mail.port' => $port,
                'mail.username' => $email,
                'mail.password' => $password,
                'mail.encryption' => $encryption,
            ]);

            DB::table('config')->where('id', 1)->update(['platform_email' => $email]);
            DB::table('config')->where('id', 1)->update(['platform_email_properties' => $platform_email_properties]);

            /*$this->setEnvironmentValue('MAIL_HOST', $host);
            $this->setEnvironmentValue('MAIL_PORT', $port);
            $this->setEnvironmentValue('MAIL_USERNAME', $username);
            $this->setEnvironmentValue('MAIL_PASSWORD', $password);
            $this->setEnvironmentValue('MAIL_ENCRYPTION', $encryption);*/


            return response()->json(['msg' => 'Email configuration changed!'], 200);
        }
        return response()->json(['msg' => 'An error occured!'], 400);
    }

    public function getPlatformEmailSettings(){

        $settings = DB::table('config')->first();
        return new ConfigSettings($settings);
    }

    /*public function setEnvironmentValue($envKey, $envValue)
    {
        $envFile = app()->environmentFilePath();
        $str = file_get_contents($envFile);

        $oldValue = strtok($str, "{$envKey}=");

        $str = str_replace("{$envKey}={$oldValue}", "{$envKey}={$envValue}\n", $str);

        $fp = fopen($envFile, 'w');
        fwrite($fp, $str);
        fclose($fp);
    }*/

}