<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class ServiceBannerImageSeeder extends Seeder
{
    /**
     * Link production service records to their banner images.
     *
     * Images live in public/uploads/services/ and the column stores the
     * path relative to public/uploads/ (rendered via asset('uploads/'.$path)).
     * Safe to re-run: only existing services are touched.
     */
    public function run(): void
    {
        foreach ($this->bannerImages() as $slug => $fileName) {
            $service = Service::query()->where('slug', $slug)->first();

            if (! $service) {
                $this->command?->warn("Skipped: no service found for slug [{$slug}].");

                continue;
            }

            if (! File::exists(public_path("uploads/services/{$fileName}"))) {
                $this->command?->warn("Skipped: banner file missing for [{$slug}] ({$fileName}).");

                continue;
            }

            $service->update(['banner_image' => "services/{$fileName}"]);
        }
    }

    /**
     * Map of service slug to its banner image file name inside uploads/services.
     *
     * @return array<string, string>
     */
    private function bannerImages(): array
    {
        return [
            // Business applications
            'hrms-development' => 'hrms-development-banner.png',
            'inventory-management' => 'inventory-management-banner.png',
            'lms-development' => 'lms-development-banner.png',
            'pos-software' => 'pos-software-banner.png',
            'real-estate-management' => 'real-estate-management-banner.png',
            'school-management-software' => 'school-management-software-banner.png',
            'visitor-management-software' => 'visitor-management-software-banner.png',

            // AI & automation
            'ai-chatbot-development' => 'ai-chatbot-development-banner.png',
            'ai-integration' => 'ai-integration-banner.png',
            'generative-ai-solutions' => 'generative-ai-solutions-banner.png',
            'machine-learning-solutions' => 'machine-learning-solutions-banner.png',
            'business-process-automation' => 'business-process-automation-banner.png',
            'ocr-document-digitization' => 'ocr-document-digitization-banner.png',
            'voice-bot-solutions' => 'voice-bot-solutions-banner.png',
            'whatsapp-automation' => 'whatsapp-automation-banner.png',

            // Infrastructure & surveillance
            'cctv-security-solutions' => 'cctv-security-solutions-banner.png',
            'access-control-systems' => 'access-control-systems-banner.png',
            'networking-solutions' => 'networking-solutions-banner.png',
            'server-management' => 'server-management-banner.png',
            'storage-solutions' => 'storage-solutions-banner.png',
            'structured-cabling' => 'structured-cabling-banner.png',
            'workstation-solutions' => 'workstation-solutions-banner.png',
            'amc-services' => 'amc-services-banner.png',

            // Cloud & security
            'aws-consulting' => 'aws-consulting-banner.png',
            'microsoft-azure-consulting' => 'microsoft-azure-consulting-banner.png',
            'google-cloud-services' => 'google-cloud-services-banner.png',
            'cloud-migration-services' => 'cloud-migration-services-banner.png',
            'cyber-security-consulting' => 'cyber-security-consulting-banner.png',
            'vapt-services' => 'vapt-services-banner.png',
            'firewall-configuration-management' => 'firewall-configuration-management-banner.png',
            'soc-support' => 'soc-security-operations-center-banner.png',
        ];
    }
}
