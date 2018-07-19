<?php

use Illuminate\Database\Seeder;

class ConfigTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('config')->delete();

        $platform_email = 'recurso_sueca@mail.dad';
        $driver = 'smtp';
        $host = 'smtp.mailtrap.io';
        $port = 2525;
        $password = '533db335670651';
        $encryption = null;
        $filesPath = 'img/decks/';
        $username = '53c18acaf64b99';
        $createdAt = Carbon\Carbon::now()->subMonths(2);

        $configInfo = [
            'platform_email' => $platform_email,
            'platform_email_properties' => "{\"username\": \"$username\", \"driver\": \"$driver\", \"host\": \"$host\", \"port\": $port, \"password\": \"$password\", \"encryption\": \"$encryption\" }",
            'img_base_path' => $filesPath,
            'created_at' => $createdAt,
            'updated_at' => $createdAt,
        ];

        DB::table('config')->insert($configInfo);
    }

}