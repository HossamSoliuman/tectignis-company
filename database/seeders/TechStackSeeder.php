<?php

namespace Database\Seeders;

use App\Models\TechStack;
use Illuminate\Database\Seeder;

class TechStackSeeder extends Seeder
{
    public function run(): void
    {
        $technologies = [
            'React', 'Flutter', 'Node.js', 'PHP', 'Python', 'Laravel',
            'AWS', 'Azure', 'Google Cloud', 'MySQL', 'PostgreSQL', 'MongoDB',
        ];

        foreach ($technologies as $index => $name) {
            TechStack::updateOrCreate(
                ['name' => $name],
                ['sort_order' => $index + 1, 'is_active' => true],
            );
        }
    }
}
