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
            // Surveillance & Access Control
            'Hikvision', 'Dahua', 'CP Plus', 'Axis', 'Bosch', 'Honeywell', 'ZKTeco', 'Matrix', 'eSSL',
            // Networking
            'Cisco', 'Fortinet', 'Palo Alto', 'Juniper', 'Aruba', 'Ubiquiti', 'D-Link', 'TP-Link', 'Netgear',
            // Servers, Storage & Workstations
            'Dell', 'HP', 'HPE', 'Lenovo', 'ASUS', 'Intel', 'AMD', 'VMware', 'Linux', 'Dell EMC',
            'NetApp', 'Synology', 'QNAP', 'Seagate', 'Western Digital', 'Microsoft',
            // Structured Cabling
            'Panduit', 'CommScope', 'Belden', 'Legrand', 'Schneider Electric',
        ];

        foreach ($technologies as $index => $name) {
            TechStack::updateOrCreate(
                ['name' => $name],
                ['sort_order' => $index + 1, 'is_active' => true],
            );
        }
    }
}
