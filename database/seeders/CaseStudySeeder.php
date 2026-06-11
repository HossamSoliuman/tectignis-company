<?php

namespace Database\Seeders;

use App\Models\CaseStudy;
use App\Models\CaseStudyCategory;
use Illuminate\Database\Seeder;

class CaseStudySeeder extends Seeder
{
    public function run(): void
    {
        $caseStudies = [
            [
                'slug' => 'perfect-packaging-solution',
                'title' => 'Perfect packaging solution',
                'category' => 'Modern Website Redesign',
                'theme' => 'blue',
                'short_description' => "Tectignis IT Solutions revamped Perfect Packer's website, ensuring a modern and visually appealing design. We crafted a fresh logo and social media assets, aligning with the latest color psychology and web design trends.",
                'image' => 'case-studies/website-designing-case-study.webp',
                'icon' => 'M9 17.25v1.007a3 3 0 0 1-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0 1 15 18.257V17.25m6-12V15a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 15V5.25m18 0A2.25 2.25 0 0 0 18.75 3H5.25A2.25 2.25 0 0 0 3 5.25m18 0H3',
                'features' => [
                    'Modern, responsive website redesign',
                    'Fresh logo & social media branding',
                    'Improved user experience & engagement',
                ],
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'slug' => 'shree-vakratunda-saree',
                'title' => 'Shree Vakratunda Saree Collection',
                'category' => 'Seamless E-Commerce Solution',
                'theme' => 'red',
                'short_description' => 'Shree Vakratunda needed a robust online platform to showcase and sell their products effectively. Tectignis designed a user-friendly e-commerce website with an intuitive interface ensuring smooth navigation and seamless transactions.',
                'image' => 'case-studies/e-commerce-development-case-study.webp',
                'icon' => 'M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z',
                'features' => [
                    'User-friendly e-commerce platform',
                    'Seamless, secure checkout experience',
                    'Intuitive navigation across devices',
                ],
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'slug' => 'mangosteen-homestays',
                'title' => 'Mangosteen Homestays',
                'category' => 'A Digital Success Story',
                'theme' => 'purple',
                'short_description' => 'Mangosteen Homestays needed a strong online presence to attract travelers and streamline bookings. Tectignis built their website from scratch with a modern design, intuitive admin panel, and SEO-optimized content.',
                'image' => 'case-studies/website-development-case-study.webp',
                'icon' => 'M2.25 15a4.5 4.5 0 0 0 4.5 4.5H18a3.75 3.75 0 0 0 1.332-7.257 3 3 0 0 0-3.758-3.848 5.25 5.25 0 0 0-10.233 2.33A4.502 4.502 0 0 0 2.25 15Z',
                'features' => [
                    'Built from scratch with modern design',
                    'Intuitive admin panel for bookings',
                    'SEO-optimized content for visibility',
                ],
                'sort_order' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($caseStudies as $index => $caseStudy) {
            $category = CaseStudyCategory::updateOrCreate(
                ['name' => $caseStudy['category']],
                ['sort_order' => $index, 'is_active' => true],
            );

            unset($caseStudy['category']);
            $caseStudy['case_study_category_id'] = $category->id;

            CaseStudy::updateOrCreate(['slug' => $caseStudy['slug']], $caseStudy);
        }
    }
}
