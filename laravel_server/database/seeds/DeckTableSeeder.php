<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DeckTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('decks')->delete();

        DB::table('decks')->insert([
            'id' => 1,
            'name' => 'first_deck',
            'hidden_face_image_path' => 'semFace.png',
            'active' => 0,
            'complete' => 0,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);

        DB::table('decks')->insert([
            'id' => 2,
            'name' => 'second_deck',
            'hidden_face_image_path' => 'semFace.png',
            'active' => 0,
            'complete' => 0,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);

        DB::table('decks')->insert([
            'id' => 3,
            'name' => 'third_deck',
            'hidden_face_image_path' => 'semFace.png',
            'active' => 0,
            'complete' => 0,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);

    }
}
