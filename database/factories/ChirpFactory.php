<?php

namespace Database\Factories;
use App\Models\Chirp;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Chirp>
 */
class ChirpFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Chirp::class;
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(), // Associe un utilisateur existant ou créé
            'message' => $this->faker->sentence(), // Générez un message aléatoire
        ];
    }
}
