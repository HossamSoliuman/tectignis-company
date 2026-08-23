<?php

namespace Database\Factories\Portal;

use App\Enums\Portal\DailyWorkCategory;
use App\Enums\Portal\DailyWorkStatus;
use App\Models\Portal\DailyWorkUpdate;
use App\Models\Portal\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DailyWorkUpdate>
 */
class DailyWorkUpdateFactory extends Factory
{
    protected $model = DailyWorkUpdate::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'employee_id' => Employee::factory(),
            'work_date' => now()->toDateString(),
            'related_category' => DailyWorkCategory::Other,
            'activity' => fake()->sentence(8),
            'start_time' => '09:30',
            'end_time' => '11:00',
            'progress' => fake()->numberBetween(0, 100),
            'status' => DailyWorkStatus::Completed,
            'remarks' => null,
        ];
    }

    public function forEmployee(Employee $employee): static
    {
        return $this->state(fn (array $attributes): array => ['employee_id' => $employee->id]);
    }

    public function onDate(string $date): static
    {
        return $this->state(fn (array $attributes): array => ['work_date' => $date]);
    }
}
