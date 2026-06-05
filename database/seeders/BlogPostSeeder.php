<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use Illuminate\Database\Seeder;

class BlogPostSeeder extends Seeder
{
    public function run(): void
    {
        $posts = [
            [
                'slug' => 'top-technology-trends-2025',
                'title' => 'Top Technology Trends to Know in 2025',
                'image' => 'blog/Artificial-Intelligence.webp',
                'published_at' => '2025-05-21 00:00:00',
                'is_published' => true,
                'excerpt' => 'In a rapidly evolving digital world, keeping pace with technological advancements is not just a competitive advantage—it\'s a necessity. As we step into 2025, businesses and individuals alike are seeking innovative solutions to improve efficiency, security, scalability, and user engagement.',
                'content' => 'In a rapidly evolving digital world, keeping pace with technological advancements is not just a competitive advantage—it\'s a necessity. As we step into 2025, businesses and individuals alike are seeking innovative solutions to improve efficiency, security, scalability, and user engagement. From artificial intelligence and machine learning to edge computing and quantum breakthroughs, the technology landscape is transforming faster than ever before. Staying informed about these trends is crucial for any business that wants to remain competitive and future-ready.',
                'seo_title' => 'Top Technology Trends to Know in 2025 | Tectignis Blog',
                'seo_description' => 'Stay updated with the latest IT and technology trends for 2025. Expert insights from Tectignis IT Solutions.',
            ],
            [
                'slug' => 'starlink-transforming-internet-india',
                'title' => 'How Starlink is Changing the Future of Internet in India',
                'image' => 'blog/Starlink-India.webp',
                'published_at' => '2025-05-08 00:00:00',
                'is_published' => true,
                'excerpt' => 'Satellite internet is reshaping connectivity across India. Starlink by SpaceX is offering high-speed broadband to remote and urban areas alike, transforming how businesses and individuals access the internet.',
                'content' => 'Satellite internet is reshaping connectivity across India. Starlink by SpaceX is offering high-speed broadband to remote and urban areas alike, transforming how businesses and individuals access the internet. For decades, millions of Indians in rural and semi-urban areas have struggled with unreliable connectivity. Starlink\'s low-earth orbit satellite network is changing this paradigm, bringing consistent speeds that rival fiber connections to places previously underserved by traditional ISPs.',
                'seo_title' => 'How Starlink is Transforming Internet Access in India | Tectignis Blog',
                'seo_description' => 'Starlink satellite internet is changing connectivity in India. Read how it affects businesses and individuals across the country.',
            ],
            [
                'slug' => 'digital-marketing-strategy-indian-startups-2025',
                'title' => 'Digital Marketing Strategy for Indian Startups 2025',
                'image' => 'blog/Digital-Marketing-Strategy.webp',
                'published_at' => '2025-04-30 00:00:00',
                'is_published' => true,
                'excerpt' => 'Around 90% of startups fail, and almost 1 in 5 don\'t even make it through their first year. Strong branding and clear communication from the beginning is critical for startup success.',
                'content' => 'Around 90% of startups fail, and almost 1 in 5 don\'t even make it through their first year. Strong branding and clear communication from the beginning is critical for startup success. For Indian startups navigating 2025\'s competitive landscape, a well-crafted digital marketing strategy is not optional—it\'s a survival necessity. From SEO and content marketing to social media presence and performance advertising, every channel plays a role in establishing credibility and driving growth.',
                'seo_title' => 'Digital Marketing Strategy for Indian Startups 2025 | Tectignis Blog',
                'seo_description' => 'Comprehensive digital marketing strategy guide for Indian startups in 2025. Learn how to grow your brand with SEO, social media, and PPC.',
            ],
        ];

        foreach ($posts as $post) {
            BlogPost::updateOrCreate(['slug' => $post['slug']], $post);
        }
    }
}
