<?php

namespace Database\Seeders;

use App\Models\Solution;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class SolutionBannerImageSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->bannerImages() as $slug => $fileName) {
            if (! File::exists(public_path("uploads/solutions/{$fileName}"))) {
                $this->command?->warn("Skipped: banner file missing for [{$slug}] ({$fileName}).");

                continue;
            }

            $solution = Solution::query()->where('slug', $slug)->first();

            if (! $solution) {
                $this->command?->warn("Skipped: no solution found for slug [{$slug}].");

                continue;
            }

            $solution->update(['banner_image' => "solutions/{$fileName}"]);
        }
    }

    /**
     * Map solution slugs to banner images stored in public/uploads/solutions.
     *
     * @return array<string, string>
     */
    private function bannerImages(): array
    {
        return [
            'erp-solutions' => 'erp-solutions-banner.png',
            'crm-solutions' => 'crm-solutions-banner.png',
            'hrms-solutions' => 'hrms-solutions-banner.png',
            'ai-solutions' => 'ai-solutions-banner.png',
            'cloud-solutions' => 'cloud-solutions-banner.png',
            'cybersecurity-solutions' => 'cybersecurity-solutions-banner.png',
            'automation-solutions' => 'automation-solutions-banner.png',
            'smart-security-solutions' => 'smart-security-solutions-banner.png',
        ];
    }
}
