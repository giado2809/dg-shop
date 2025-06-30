<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

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
            //
        'name' => fake()->words(2, true),
        'image' => fake()->imageUrl(300, 300, 'fashion', true), // ảnh giả
        'price' => fake()->randomFloat(2, 100000, 1000000),
        'description' => fake()->paragraph(2),
        'category_id' => \App\Models\Category::inRandomOrder()->first()?->id ?? 1,
        ];
    }
}
