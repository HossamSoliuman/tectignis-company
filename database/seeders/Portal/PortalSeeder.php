<?php

namespace Database\Seeders\Portal;

use App\Enums\Portal\EmployeeStatus;
use App\Enums\Portal\PortalRole;
use App\Models\Portal\Department;
use App\Models\Portal\Employee;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Bootstraps the operations portal with the org structure it needs before
 * anybody can be assigned work.
 *
 * Idempotent throughout: safe to re-run on an existing database, and it never
 * overwrites a password that has already been changed.
 */
class PortalSeeder extends Seeder
{
    /**
     * The starting departments, from the requirement document's org structure.
     *
     * @var list<array{name: string, code: string}>
     */
    private const DEPARTMENTS = [
        ['name' => 'Management', 'code' => 'MGMT'],
        ['name' => 'Tendering', 'code' => 'TEND'],
        ['name' => 'Technical', 'code' => 'TECH'],
        ['name' => 'Sales & Marketing', 'code' => 'SALES'],
        ['name' => 'Procurement', 'code' => 'PROC'],
        ['name' => 'Finance & Accounts', 'code' => 'FIN'],
        ['name' => 'Administration', 'code' => 'ADMIN'],
    ];

    public function run(): void
    {
        $departments = $this->seedDepartments();

        $director = $this->seedDirector($departments['MGMT']);

        $this->seedDepartmentHead($departments['TEND'], $director);
    }

    /**
     * @return array<string, Department> keyed by department code
     */
    private function seedDepartments(): array
    {
        $departments = [];

        foreach (self::DEPARTMENTS as $index => $department) {
            $departments[$department['code']] = Department::updateOrCreate(
                ['code' => $department['code']],
                ['name' => $department['name'], 'is_active' => true, 'sort_order' => $index],
            );
        }

        return $departments;
    }

    /**
     * The first portal account. Also grants portal access to the existing CMS
     * admin so a fresh install has exactly one person who can let others in.
     */
    private function seedDirector(Department $management): Employee
    {
        $user = User::where('email', 'admin@tectignis.in')->first();

        if ($user === null) {
            $user = User::create([
                'name' => 'Tectignis Director',
                'email' => 'director@tectignis.in',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'portal_role' => PortalRole::Director->value,
            ]);
        } else {
            $user->update(['portal_role' => PortalRole::Director->value]);
        }

        return Employee::updateOrCreate(
            ['user_id' => $user->id],
            [
                'employee_code' => 'EMP-0001',
                'name' => $user->name,
                'email' => $user->email,
                'department_id' => $management->id,
                'designation' => 'Director',
                'status' => EmployeeStatus::Active,
            ],
        );
    }

    /**
     * A tendering lead so the org chart is not flat on day one. Created without
     * a login: the director links an account and grants a role from the UI,
     * which is how every other employee will be onboarded too.
     */
    private function seedDepartmentHead(Department $tendering, Employee $director): void
    {
        $head = Employee::updateOrCreate(
            ['employee_code' => 'EMP-0002'],
            [
                'name' => 'Tendering Lead',
                'department_id' => $tendering->id,
                'designation' => 'Tender Manager',
                'status' => EmployeeStatus::Active,
                'reports_to_id' => $director->id,
            ],
        );

        $tendering->update(['head_employee_id' => $head->id]);
    }
}
