<?php

namespace Database\Factories;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

class TaskFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Task::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'status' => $this->faker->randomDigit(),
            'type' => rand(0,9),
            'title' => $this->faker->sentence(),
            'content' => $this->faker->text(),
            'user_id' => $this->faker->randomElement(User::all()->pluck('id')->toArray()),
            'admin_id' => $this->faker->randomElement([1, 2, 3]),
            'end_date' => $this->faker->date(),
            'archived_at' => $this->faker->optional()->date(),
        ];
    }
    // DB::table('users')->where('is_admin', true)->value('id')
}
