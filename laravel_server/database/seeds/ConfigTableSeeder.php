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

        $platform_email = 'projetosuecadad@gmail.com';
        $driver = 'smtp';
        $host = 'smtp.gmail.com';
        $port = 587;
        $password = 'Suecadad2018';
        $encryption = 'tls';
        $filesPath = 'img/decks/';
        $createdAt = Carbon\Carbon::now()->subMonths(2);

        $configInfo = [
            'platform_email' => $platform_email,
            'platform_email_properties' => "{\"driver\": \"$driver\", \"host\": \"$host\", \"port\": $port, \"password\": \"$password\", \"encryption\": \"$encryption\" }",
            'img_base_path' => $filesPath,
            'created_at' => $createdAt,
            'updated_at' => $createdAt,
        ];

        DB::table('config')->insert($configInfo);
    }

}