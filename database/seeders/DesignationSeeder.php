<?php

namespace Database\Seeders;

use App\Models\Designation;
use Illuminate\Database\Seeder;

/**
 * Starter designation list for the careers form dropdown (spec §28.6), until
 * HR replaces it under Careers → Designations. Re-running never overwrites
 * names, order or status that were changed in the admin.
 */
class DesignationSeeder extends Seeder
{
    /**
     * @var list<array{0: string, 1: string}> name, department
     */
    private const DESIGNATIONS = [
        ['Software Developer', 'Development'],
        ['Senior Software Developer', 'Development'],
        ['Full Stack Developer', 'Development'],
        ['AI/ML Engineer', 'AI & Automation'],
        ['DevOps Engineer', 'DevOps'],
        ['Cloud Engineer', 'Cloud & Infrastructure'],
        ['Network Engineer', 'IT Infrastructure'],
        ['Cybersecurity Analyst', 'Security'],
        ['QA Engineer', 'Quality Assurance'],
        ['UI/UX Designer', 'Design'],
        ['Project Manager', 'Delivery'],
        ['Business Analyst', 'Delivery'],
        ['Digital Marketing Executive', 'Marketing'],
        ['Sales Executive', 'Sales'],
        ['HR Executive', 'Human Resources'],
        ['Intern', 'General'],
    ];

    public function run(): void
    {
        foreach (self::DESIGNATIONS as $order => [$name, $department]) {
            Designation::firstOrCreate(
                ['name' => $name],
                ['department' => $department, 'sort_order' => ($order + 1) * 10, 'is_active' => true],
            );
        }
    }
}
