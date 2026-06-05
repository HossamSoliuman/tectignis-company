<?php

namespace Database\Seeders;

use App\Models\TechStack;
use Illuminate\Database\Seeder;

class TechStackSeeder extends Seeder
{
    public function run(): void
    {
        $technologies = [
            // Frontend
            'React', 'Vue.js', 'Angular', 'Next.js',
            // Backend
            'Node.js', 'PHP', 'Laravel', 'Python', 'Django', 'Java', '.NET',
            // Mobile
            'Flutter', 'React Native', 'Kotlin', 'Swift',
            // CMS & E-commerce
            'WordPress', 'Shopify', 'Magento',
            // Databases
            'MySQL', 'PostgreSQL', 'MongoDB', 'Redis',
            // Cloud & DevOps
            'AWS', 'Azure', 'Google Cloud', 'Docker', 'Kubernetes',
            // AI / ML
            'TensorFlow', 'PyTorch', 'OpenAI', 'LangChain',
        ];

        foreach ($technologies as $index => $name) {
            TechStack::updateOrCreate(
                ['name' => $name],
                ['sort_order' => $index + 1, 'is_active' => true],
            );
        }
    }
}
