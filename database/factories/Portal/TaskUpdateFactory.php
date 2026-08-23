<?php

namespace Database\Factories\Portal;

use App\Models\Portal\Employee;
use App\Models\Portal\Task;
use App\Models\Portal\TaskUpdate;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TaskUpdate>
 */
class TaskUpdateFactory extends Factory
{
    protected $model = TaskUpdate::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'task_id' => Task::factory(),
            'employee_id' => Employee::factory(),
            'note' => fake()->sentence(10),
            'progress' => fake()->numberBetween(0, 100),
            'status_from' => null,
            'status_to' => null,
            'logged_at' => now(),
        ];
    }
}
