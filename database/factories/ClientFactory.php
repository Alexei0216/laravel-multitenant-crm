<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClientFactory extends Factory
{
    protected $model = Client::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->unique()->phoneNumber(),
            'tenant_id' => Tenant::factory(),
        ];
    }

    public function withoutEmail(): static
    {
        return $this->state(fn() => ['email' => null]);
    }

    public function withoutPhone(): static
    {
        return $this->state(fn() => ['phone' => null]);
    }
}
