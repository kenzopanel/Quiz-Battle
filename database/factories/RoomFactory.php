<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Room;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Room>
 */
class RoomFactory extends Factory
{
    protected $model = Room::class;

    public function definition(): array
    {
        return [
            'name' => fake()->words(3, true),
            'category_id' => Category::factory(),
            'creator_session_token' => Str::uuid()->toString(),
            'status' => 'waiting',
            'max_players' => 2,
            'player_tokens' => null,
            'expires_at' => now()->addMinutes(30),
        ];
    }

    public function withPlayers(array $playerTokens): static
    {
        return $this->state(fn(array $attributes) => [
            'player_tokens' => $playerTokens,
        ]);
    }

    public function ongoing(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'ongoing',
        ]);
    }

    public function finished(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'finished',
        ]);
    }

    public function expired(): static
    {
        return $this->state(fn(array $attributes) => [
            'expires_at' => now()->subMinutes(10),
        ]);
    }
}
