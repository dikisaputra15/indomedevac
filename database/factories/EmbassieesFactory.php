<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Embassiees>
 */
class EmbassieesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'province_id' => $this->faker->numberBetween(1, 22),
            'name_embassiees' => $this->faker->company . ' Embassy',
            'location' => $this->faker->address,
            'telephone' => $this->faker->phoneNumber,
            'fax' => $this->faker->phoneNumber,
            'email' => $this->faker->unique()->safeEmail,
            'website' => $this->faker->url,
            'latitude' => $this->faker->latitude(-11.0, -1.0), // Papua New Guinea
            'longitude' => $this->faker->longitude(140.0, 155.0),
            'image' => $this->faker->randomElement([
                'https://pg.concordreview.com/wp-content/uploads/2025/01/Autralia-Consulate-Lea.jpg',
                'https://pg.concordreview.com/wp-content/uploads/2024/08/Embassy-of-the-Indonesia-1-scaled.jpg',
                'https://pg.concordreview.com/wp-content/uploads/2024/08/High-Commission-of-Malaysia-1.jpg',
                'https://pg.concordreview.com/wp-content/uploads/2024/08/Foto_03.Embassy-of-Japan-Papua-New-Guinea-2024.jpg',
                'https://pg.concordreview.com/wp-content/uploads/2024/08/Foto_04.Embassy-of-France-Papua-New-Guinea-2024.jpg',
            ]),
        ];
    }
}
