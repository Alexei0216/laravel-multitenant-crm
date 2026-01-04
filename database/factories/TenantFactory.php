<?php

namespace Database\Factories;

use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

class TenantFactory extends Factory
{
    protected $model = Tenant::class;

    public function definition(): array
    {
        $name = $this->faker->company;

        return [
            'name' => $name,
            'slug' => $this->faker->unique()->slug,
            'email' => $this->faker->unique()->companyEmail,
            'phone' => $this->faker->phoneNumber,
            'address' => $this->faker->address,
            'status' => $this->faker->randomElement(['active', 'inactive']),
            'settings' => json_encode([]),
        ];
    }
}
