<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => Str::title(fake()->words(random_int(1, 4), true)),
            'category_id' => Category::all()->random()->id,
            'description' => fake()->paragraphs(random_int(1, 3), true),
            'date_time' => fake()->dateTimeBetween('-2 months', 'now')->format('Y-m-d H:i:s'),
        ];
    }
}
