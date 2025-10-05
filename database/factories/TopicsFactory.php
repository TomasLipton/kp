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
            'slug' => $this->faker->slug(),
            'name_ru' => $this->faker->word(),
            'description_ru' => $this->faker->text(),
            'name_pl' => $this->faker->word(),
            'name_by' => $this->faker->word(),
            'name_uk' => $this->faker->word(),
            'description_pl' => $this->faker->text(),
            'description_by' => $this->faker->text(),
            'description_uk' => $this->faker->text(),
            'picture' => $this->faker->word(),
            'parent_id' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
