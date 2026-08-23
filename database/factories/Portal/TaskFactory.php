<?php

namespace Database\Factories\Portal;

use App\Enums\Portal\TaskPriority;
use App\Enums\Portal\TaskStatus;
use App\Models\Portal\Employee;
use App\Models\Portal\Task;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Task>
 */
class TaskFactory extends Factory
{
    protected $model = Task::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => 'TSK-'.Str::padLeft((string) fake()->unique()->numberBetween(1, 999999), 6, '0'),
            'title' => Str::headline(fake()->words(4, true)),
            'description' => fake()->sentence(12),
            'assigned_to_id' => Employee::factory(),
            'created_by_id' => Employee::factory(),
            'priority' => TaskPriority::Medium,
            'status' => TaskStatus::NotStarted,
            'progress' => 0,
            'start_date' => now(),
            'due_date' => now()->addDays(5),
            'completed_at' => null,
            'is_overdue' => false,
            'reopened_count' => 0,
        ];
    }

    public function assignedTo(Employee $employee): static
    {
        return $this->state(fn (array $attributes): array => ['assigned_to_id' => $employee->id]);
    }

    /**
     * Past due and still open — the shape the overdue engine must catch.
     */
    public function overdue(): static
    {
        return $this->state(fn (array $attributes): array => [
            'due_date' => now()->subDays(3),
            'status' => TaskStatus::InProgress,
        ]);
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => TaskStatus::Completed,
            'progress' => 100,
            'completed_at' => now(),
            'is_overdue' => false,
        ]);
    }

    public function dueToday(): static
    {
        return $this->state(fn (array $attributes): array => [
            'due_date' => now()->endOfDay(),
        ]);
    }
}
