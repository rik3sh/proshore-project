<?php

namespace Database\Factories;

use App\Models\Option;
use App\Models\Question;
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
    public function definition(): array
    {
        return [
            'title' => fake()->sentence . '?',
            'subject' => fake()->randomElement(['physics', 'chemistry']),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    /**
     * After creating a question, create and attach options to it.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function with_options(): Factory
    {
        return $this->afterCreating(function (Question $question) {
            // Make 3 incorrect options
            $options = Option::factory()->count(3)->make([
                'question_id' => $question->id,
                'is_correct' => false
            ]);

            // Make a correct option
            $correctOption = Option::factory()->count(1)->make([
                'question_id' => $question->id,
                'is_correct' => true,
            ]);

            // Concatenate all options together
            $options = $options->concat($correctOption);

            // Shuffle the options
            $shuffledOptions = $options->shuffle();

            // Attach each option to the question with the question_id & save them
            $shuffledOptions->each->save();    
        });
    }
}
