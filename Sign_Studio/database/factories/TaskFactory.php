<?php

namespace Database\Factories;

use App\Models\Task;
use App\Models\Order;
use App\Models\Department;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition(): array
    {
        $plannedStart = $this->faker->dateTimeBetween('-7 days', '+7 days');
        $plannedEnd   = $this->faker->dateTimeBetween($plannedStart, '+14 days');

        return [
            'order_id'           => Order::first()?->id ?? 1,
            'department_id'      => Department::first()?->id ?? 1,
            'assigned_to'        => User::first()?->id ?? 1,
            'task_number'        => 'TSK-' . strtoupper(Str::random(6)),
            'title'              => $this->faker->sentence(5),
            'description'        => $this->faker->paragraph(),
            'priority'           => $this->faker->randomElement(['low', 'normal', 'high', 'urgent']),
            'planned_start'      => $plannedStart,
            'planned_end'        => $plannedEnd,
            'planned_time_hours' => $this->faker->randomFloat(2, 1, 48),
            'status'             => $this->faker->randomElement(['pending', 'in_progress', 'completed']),
            'created_by'         => User::first()?->id ?? 1,
        ];
    }

    public function pending(): static
    {
        return $this->state(fn () => ['status' => 'pending']);
    }

    public function inProgress(): static
    {
        return $this->state(fn () => ['status' => 'in_progress', 'actual_start' => now()]);
    }

    public function completed(): static
    {
        return $this->state(fn () => [
            'status'            => 'completed',
            'actual_start'      => now()->subHours(8),
            'actual_end'        => now(),
            'actual_time_hours' => 8.0,
            'tat_duration_hours' => 8.0,
        ]);
    }
}
