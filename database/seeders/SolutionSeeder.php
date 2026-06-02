<?php

namespace Database\Seeders;

use App\Models\Solution;
use Illuminate\Database\Seeder;

class SolutionSeeder extends Seeder
{
    public function run(): void
    {
        $solutions = [
            ['slug' => 'erp-solutions', 'title' => 'ERP Solutions', 'icon' => 'fas fa-database', 'short_description' => 'Streamline operations with integrated enterprise resource planning systems tailored to your business.', 'sort_order' => 1],
            ['slug' => 'crm-solutions', 'title' => 'CRM Solutions', 'icon' => 'fas fa-handshake', 'short_description' => 'Manage customer relationships, sales pipelines, and support with powerful CRM platforms.', 'sort_order' => 2],
            ['slug' => 'hrms-solutions', 'title' => 'HRMS Solutions', 'icon' => 'fas fa-users', 'short_description' => 'Automate HR, payroll, attendance, and employee management end to end.', 'sort_order' => 3],
            ['slug' => 'ai-solutions', 'title' => 'AI Solutions', 'icon' => 'fas fa-robot', 'short_description' => 'Leverage AI chatbots, automation, OCR, and machine learning to scale your business.', 'sort_order' => 4],
            ['slug' => 'cloud-solutions', 'title' => 'Cloud Solutions', 'icon' => 'fas fa-cloud', 'short_description' => 'Migrate, host, and scale on AWS, Azure, and Google Cloud with confidence.', 'sort_order' => 5],
            ['slug' => 'cybersecurity-solutions', 'title' => 'Cybersecurity Solutions', 'icon' => 'fas fa-shield-alt', 'short_description' => 'Protect your business with VAPT, firewalls, SOC, and compliance services.', 'sort_order' => 6],
            ['slug' => 'automation-solutions', 'title' => 'Automation Solutions', 'icon' => 'fas fa-cogs', 'short_description' => 'Automate repetitive workflows and processes to boost productivity.', 'sort_order' => 7],
            ['slug' => 'smart-security-solutions', 'title' => 'Smart Security Solutions', 'icon' => 'fas fa-video', 'short_description' => 'CCTV, access control, visitor management, and biometric security systems.', 'sort_order' => 8],
        ];

        foreach ($solutions as $solution) {
            Solution::updateOrCreate(['slug' => $solution['slug']], array_merge($solution, ['is_active' => true]));
        }
    }
}
