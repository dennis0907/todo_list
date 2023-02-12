<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Todo;
use App\Models\User;
use Illuminate\Support\Str;

class TodoFactory extends Factory
{
    protected $model = Todo::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->name,
            'content' => $this->faker->text,
            'finished' => $this->faker->boolean,
            'todo_time' => $this->faker->dateTimeThisYear(),
            'type_id' => $this->faker->numberBetween(1,3),
            'user_id' => User::all()->random()->id
        ];
    }
}
