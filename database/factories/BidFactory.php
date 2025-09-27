<?php

namespace Database\Factories;

use App\Models\Bid;
use App\Models\Auction;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Bid>
 */
class BidFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Bid::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'auction_id' => Auction::factory(),
            'user_id' => User::factory(),
            'amount' => $this->faker->randomNumber(7),
            'equity_percentage' => $this->faker->randomFloat(2, 1, 50),
            'message' => $this->faker->sentence(),
            'status' => 'active',
            'bid_time' => now(),
        ];
    }
}