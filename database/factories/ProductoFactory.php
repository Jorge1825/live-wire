<?php

namespace Database\Factories;

use App\Models\Producto;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Producto>
 */
class ProductoFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'nombre' => ucfirst(fake()->unique()->words(2, true)),
            'descripcion' => fake()->sentence(),
            'precio' => fake()->randomFloat(2, 1, 500),
            'stock' => fake()->numberBetween(0, 100),
        ];
    }
}
