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
        $categories = ['Informatique', 'Téléphonie', 'Audio', 'Accessoires', 'Électronique', 'Jeux'];
        
        return [
            'nom_produit' => $this->faker->words(3, true), 
            'code_produit' => strtoupper($this->faker->bothify('???-###')),
            'prix' => $this->faker->randomFloat(2, 10, 1000),
            'quantite' => $this->faker->numberBetween(0, 100),
            'image' => $this->faker->optional(0.7)->word() ? 'images/' . $this->faker->word() . '.jpg' : null,
            'fichier_pdf' => $this->faker->optional(0.3)->word() ? 'documents/' . $this->faker->word() . '.pdf' : null,
            'categorie' => $this->faker->randomElement($categories),
            'statut' => $this->faker->randomElement(['actif', 'inactif']),
        ];
    }

    /**
     * Indicate that the product is active.
     */
    public function actif(): static
    {
        return $this->state(fn (array $attributes) => [
            'statut' => 'actif',
        ]);
    }

    /**
     * Indicate that the product is inactive.
     */
    public function inactif(): static
    {
        return $this->state(fn (array $attributes) => [
            'statut' => 'inactif',
        ]);
    }

    /**
     * Indicate that the product is in stock.
     */
    public function enStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'quantite' => $this->faker->numberBetween(1, 100),
        ]);
    }

    /**
     * Indicate that the product is out of stock.
     */
    public function rupture(): static
    {
        return $this->state(fn (array $attributes) => [
            'quantite' => 0,
        ]);
    }
}