<?php

namespace Database\Factories\Portal;

use App\Enums\Portal\EmployeeStatus;
use App\Enums\Portal\PortalRole;
use App\Models\Portal\Department;
use App\Models\Portal\Employee;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Employee>
 */
class EmployeeFactory extends Factory
{
    protected $model = Employee::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => null,
            'employee_code' => 'EMP-'.Str::padLeft((string) fake()->unique()->numberBetween(1, 99999), 5, '0'),
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->numerify('+91 ##########'),
            'department_id' => Department::factory(),
            'designation' => fake()->jobTitle(),
            'date_of_joining' => fake()->dateTimeBetween('-5 years', 'now'),
            'status' => EmployeeStatus::Active,
            'reports_to_id' => null,
            'notes' => null,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes): array => ['status' => EmployeeStatus::Inactive]);
    }

    /**
     * An employee with a login account at the given portal role.
     */
    public function withUser(PortalRole $role = PortalRole::Employee): static
    {
        return $this->state(fn (array $attributes): array => [
            'user_id' => User::factory()->portal($role),
        ]);
    }

    public function reportingTo(Employee $manager): static
    {
        return $this->state(fn (array $attributes): array => [
            'reports_to_id' => $manager->id,
            'department_id' => $manager->department_id,
        ]);
    }
}
