<?php

namespace Database\Seeders;

use App\Models\CaseStudy;
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
                'short_description' => "Tectignis IT Solutions revamped Perfect Packer's website, ensuring a modern and visually appealing design. We crafted a fresh logo and social media assets, aligning with the latest color psychology and web design trends.",
                'image' => 'website-designing-case-study.webp',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'slug' => 'shree-vakratunda-saree',
                'title' => 'Shree Vakratunda Saree Collection',
                'category' => 'Seamless E-Commerce Solution',
                'short_description' => 'Shree Vakratunda needed a robust online platform to showcase and sell their products effectively. Tectignis designed a user-friendly e-commerce website with an intuitive interface ensuring smooth navigation and seamless transactions.',
                'image' => 'e-commerce-development-case-study.webp',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'slug' => 'mangosteen-homestays',
                'title' => 'Mangosteen Homestays',
                'category' => 'A Digital Success Story',
                'short_description' => 'Mangosteen Homestays needed a strong online presence to attract travelers and streamline bookings. Tectignis built their website from scratch with a modern design, intuitive admin panel, and SEO-optimized content.',
                'image' => 'website-development-case-study.webp',
                'sort_order' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($caseStudies as $caseStudy) {
            CaseStudy::updateOrCreate(['slug' => $caseStudy['slug']], $caseStudy);
        }
    }
}
