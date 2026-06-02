<?php

namespace Database\Seeders;

use App\Models\Capability;
use Illuminate\Database\Seeder;

class CapabilitySeeder extends Seeder
{
    public function run(): void
    {
        $capabilities = [
            [
                'slug' => 'custom-software-development',
                'title' => 'Software Development',
                'icon' => 'Software-Development.webp',
                'short_description' => 'Web Apps · Mobile Apps · ERP · CRM · SaaS',
                'sort_order' => 1,
            ],
            [
                'slug' => 'ai-solutions',
                'title' => 'AI & Automation',
                'icon' => 'Custom-Software.webp',
                'short_description' => 'AI Chatbots · WhatsApp Automation · OCR · ML · Voice Bots',
                'sort_order' => 2,
            ],
            [
                'slug' => 'cloud-solutions',
                'title' => 'Cloud & Infrastructure',
                'icon' => 'Networking-Solutions.webp',
                'short_description' => 'AWS · Azure · Google Cloud · Migration · Hosting',
                'sort_order' => 3,
            ],
            [
                'slug' => 'cybersecurity-solutions',
                'title' => 'Cybersecurity',
                'icon' => 'CCTV-Systems.webp',
                'short_description' => 'VAPT · Firewall · Security SOC · Support · Compliance',
                'sort_order' => 4,
            ],
            [
                'slug' => 'networking-solutions',
                'title' => 'Networking & Hardware',
                'icon' => 'Web-Design-and-Development.webp',
                'short_description' => 'Networking · Servers · Storage · Workstations · AMC',
                'sort_order' => 5,
            ],
            [
                'slug' => 'cctv-security',
                'title' => 'Smart Security',
                'icon' => 'MobileApp-Development.webp',
                'short_description' => 'CCTV · Access Control · Visitor Management · Biometrics',
                'sort_order' => 6,
            ],
        ];

        foreach ($capabilities as $capability) {
            Capability::updateOrCreate(['slug' => $capability['slug']], array_merge($capability, ['is_active' => true]));
        }
    }
}
