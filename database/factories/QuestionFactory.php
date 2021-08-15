<?php

namespace Database\Factories;

use App\Models\Question;
use App\Models\Section;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuestionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = question::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "front"=>$this->faker->paragraph(2,true),
            "back"=>$this->faker->paragraph(2,true),
        ];
    }
}
