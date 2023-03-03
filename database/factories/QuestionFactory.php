<?php

namespace Database\Factories;

use App\Models\Subject;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Question>
 */
class QuestionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $type = rand(1,3);
        if ($type != 1) {
            $media = $this->faker->imageUrl(640, 480, 'cats');
        }
        else {
            $media = null;
        }
        $subject = Subject::inRandomOrder()->first();
        return [
            'question_type' => $type,
            'subject_id' => $subject->id,
            'content' => $this->faker->text($maxNbChars = 100),
            'media' => $media,
            'answer1' => $this->faker->sentence($nbWords = rand(3,9), $variableNbWords = true),
            'answer2' => $this->faker->sentence($nbWords = rand(3,9), $variableNbWords = true),
            'answer3' => $this->faker->sentence($nbWords = rand(3,9), $variableNbWords = true),
            'answer4' => $this->faker->sentence($nbWords = rand(3,9), $variableNbWords = true),
            'correct_answer' => rand(1,4),
            'question_level' => 1
        ];
    }
}
