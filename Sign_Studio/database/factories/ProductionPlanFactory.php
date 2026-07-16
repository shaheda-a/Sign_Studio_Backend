<?php

namespace Database\Factories;

use App\Models\ProductionPlan;
use App\Models\Order;
use App\Models\JobCard;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductionPlanFactory extends Factory
{
    protected $model = ProductionPlan::class;

    public function definition(): array
    {
        $startDate = $this->faker->dateTimeBetween('-7 days', '+7 days');
        $endDate   = $this->faker->dateTimeBetween($startDate, '+21 days');

        return [
            'order_id'    => Order::first()?->id ?? 1,
            'job_card_id' => JobCard::first()?->id ?? 1,
            'plan_number' => 'PP-' . strtoupper(Str::random(6)),
            'start_date'  => $startDate,
            'end_date'    => $endDate,
            'status'      => $this->faker->randomElement(['planned', 'in_progress', 'completed', 'on_hold']),
            'notes'       => $this->faker->sentence(),
            'created_by'  => User::first()?->id ?? 1,
        ];
    }

    public function planned(): static
    {
        return $this->state(fn () => ['status' => 'planned']);
    }

    public function inProgress(): static
    {
        return $this->state(fn () => ['status' => 'in_progress', 'actual_start' => now()->toDateString()]);
    }
}
