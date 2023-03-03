<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use JsonMachine\Items;
use App\Jobs\handJmdictFurigana;
use Illuminate\Support\Facades\Redis;

class FuriganaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $path = base_path('database/data/JmdictFurigana.json');
        $fruits = Items::fromFile($path);
        set_time_limit(0);
        foreach ($fruits as $name => $data) {
            $job = (new handJmdictFurigana($data->text, $data->reading, json_encode($data->furigana)));
            dispatch($job);
        }
    }
}
