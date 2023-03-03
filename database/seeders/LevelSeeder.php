<?php

namespace Database\Seeders;

use App\Models\Level;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Level::create([
            'level' => 0,
            'percent_correct' => 0
        ]);
        Level::create([
            'level' => 10,
            'percent_correct' => 5,
            'thumbnail' => env('APP_URL').'/backend/images/levels/level10.png'
        ]);
        $percent = 10;
        for ($i = 9; $i >= 1; $i--){
            Level::create([
                'level' => $i,
                'percent_correct' => $percent,
                'thumbnail' => env('APP_URL').'/backend/images/levels/level'.$i.'.png'
            ]);
            $percent+=10;
        }
    }
}
