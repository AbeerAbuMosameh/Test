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
            'image' => $this->faker->imageUrl(),
            'name' => $this->faker->word,
            'model' => $this->faker->word,
            'sku' => $this->faker->unique()->regexify('[A-Z0-9]{10}'),
            'quantity' => $this->faker->numberBetween(1, 100),
            'price' => $this->faker->randomFloat(2, 10, 1000),
            'discount_price' => $this->faker->optional()->randomFloat(2, 5, 500),
            'cost_price' => $this->faker->optional()->randomFloat(2, 5, 500),
            'rate' => $this->faker->optional()->randomFloat(1, 1, 5),
            'slug' => $this->faker->unique()->slug,
            'keyword' => $this->faker->optional()->word,
            'meta_title' => $this->faker->optional()->sentence,
            'meta_description' => $this->faker->optional()->paragraph,
            'product_tag' => $this->faker->optional()->word,
            'status' => $this->faker->randomElement(['active', 'inactive']),
            'in_stock' => $this->faker->randomElement(['yes', 'no']),
            'limited_inStock' => $this->faker->randomElement(['yes', 'no']),
            'width' => $this->faker->randomFloat(2, 1, 100),
            'height' => $this->faker->randomFloat(2, 1, 100),
            'weight' => $this->faker->randomFloat(2, 1, 100),
            'length' => $this->faker->randomFloat(2, 1, 100),
            'description' => $this->faker->paragraph,

        ];
    }
}
