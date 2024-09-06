<?php

namespace Database\Factories;

use App\Models\Topics;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class TopicsFactory extends Factory
{
    protected $model = Topics::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'title' => $this->faker->word(),
            'description' => $this->faker->text(),
            'picture' => $this->faker->word(),
            'parent_id' => $this->faker->randomNumber(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
