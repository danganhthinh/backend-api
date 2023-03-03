<?php

namespace Database\Seeders;

use App\Models\CategorySubject;
use App\Models\Subject;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $subjects = [
            [
                'name' => 'ルールやモラル',
                'category' => 'Category 1',
            ],
            [
                'name' => '助言や注意',
                'category' => 'Category 1',
            ],
            [
                'name' => '学業',
                'category' => 'Category 1',
            ],
            [
                'name' => '挨拶や感謝',
                'category' => 'Category 2',
            ],
            [
                'name' => '発言や説明',
                'category' => 'Category 3',
            ],
            [
                'name' => '仲間づくり',
                'category' => 'Category 4',
            ],
            [
                'name' => '思いやり',
                'category' => 'Category 5',
            ],
            [
                'name' => '拒否',
                'category' => 'Category 6',
            ],
            [
                'name' => '緊張',
                'category' => 'Category 7',
            ],
            [
                'name' => '称賛',
                'category' => 'Category 8',
            ],
            [
                'name' => '相談',
                'category' => 'Category 9',
            ],
            [
                'name' => '自律',
                'category' => 'Category 10',
            ],
            [
                'name' => 'リーダーシップ',
                'category' => 'Category 10',
            ],
        ];

        foreach ($subjects as $item) {
            $category = CategorySubject::where('name', $item['category'])->first();
            Subject::create([
                'name' => $item['name'],
                'category_subject_id' => $category->id
            ]);
        }
    }
}
