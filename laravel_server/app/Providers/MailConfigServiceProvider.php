<?php

namespace App\Providers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class MailConfigServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */

    public function register()
    {
        if (\Schema::hasTable('config')) {
            $mail = DB::table('config')->first();

            $email_configuration_data = json_decode((DB::table('config')->first()->platform_email_properties), true);
            $email = DB::table('config')->first()->platform_email;

            if ($mail) //checking if table is not empty
            {
                $config = array(
                    'driver'     => 'smtp',
                    'host'       => $email_configuration_data['host'],
                    'port'       => $email_configuration_data['port'],
                    'from'       => $email,
                    'encryption' => $email_configuration_data['encryption'],
                    'username'   => $email,
                    'password'   => $email_configuration_data['password'],
                    'sendmail'   => '/usr/sbin/sendmail -bs',
                    'pretend'    => false,
                );
                Config::set('mail', $config);
            }
        }
    }
}