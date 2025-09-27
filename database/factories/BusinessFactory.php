<?php

namespace Database\Factories;

use App\Models\Business;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Business>
 */
class BusinessFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Business::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => $this->faker->company(),
            'description' => $this->faker->paragraph(),
            'industry' => $this->faker->word(),
            'location' => $this->faker->city(),
            'website' => $this->faker->url(),
            'social_media' => json_encode([
                'facebook' => $this->faker->url(),
                'instagram' => $this->faker->url(),
                'twitter' => $this->faker->url()
            ]),
        ];
    }
}