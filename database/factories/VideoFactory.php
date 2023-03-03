<?php

namespace Database\Factories;

use App\Models\Subject;
use App\Models\Video;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory
 */
class VideoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $media = $this->faker->imageUrl(640, 480, 'cats');
        $level = 10;
        $subject = Subject::inRandomOrder()->first();
        return [
            'subject_id' => $subject->id,
            'title' => $this->faker->text($maxNbChars = 40),
            'file_path' => $media,
            'thumbnail' =>  $media,
            'video_level' => $level
        ];
    }
}
