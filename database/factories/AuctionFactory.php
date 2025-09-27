<?php

namespace Database\Factories;

use App\Models\Auction;
use App\Models\Business;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Auction>
 */
class AuctionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Auction::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'business_id' => Business::factory(),
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'funding_goal' => $this->faker->randomNumber(7),
            'equity_percentage' => $this->faker->randomFloat(2, 1, 50),
            'minimum_bid' => $this->faker->randomNumber(6),
            'current_highest_bid' => 0,
            'current_highest_bidder_id' => null,
            'start_date' => now()->subDay(),
            'end_date' => now()->addDays($this->faker->numberBetween(1, 30)),
            'status' => 'active',
            'terms_conditions' => json_encode([
                'condition_1' => $this->faker->sentence(),
                'condition_2' => $this->faker->sentence()
            ]),
        ];
    }
}