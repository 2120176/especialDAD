<?php

use Illuminate\Database\Seeder;

class CardsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $nbrCards = 53;

        for ($i = 1; $i <= $nbrCards; ++$i) {
            DB::table('cards')->insert($this->insertCard($i));
        }

        DB::table('cards')->insert($this->insertCard('semFace', 'Heart', 'null'));

    }

    private function insertCard($imgName, $suite = 'Heart', $value = '10')
    {
        $createdAt = Carbon\Carbon::now();

        return [
            'value' => $value,
            'suite' => $suite,
            'deck_id' => 1,
            'path' => $imgName.'.png',
            'created_at' => $createdAt,
            'updated_at' => $createdAt,
        ];
    }
}
