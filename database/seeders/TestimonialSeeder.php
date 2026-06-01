<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        $testimonials = [
            [
                'name' => 'Amit Jadhav',
                'rating' => 5,
                'quote' => 'From the initial consultation to the final product delivery, Tectignis made the entire website design process seamless and enjoyable. I highly recommend them to anyone in need of professional web design company navi mumbai.',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Nidhi Patil',
                'rating' => 5,
                'quote' => 'Tectignis is not just about aesthetics they are results-driven. My website not only looks fantastic but also performs exceptionally well, translating into increased traffic and conversions for my business. Do approach them if you want high results.',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Amit Jana',
                'rating' => 5,
                'quote' => 'Tectignis, the trailblazers of web development in Navi Mumbai! Their team\'s commitment to delivering quality and innovation is remarkable. My website now stands out, thanks to Tectignis. Choose them for a seamless web development journey.',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Abhya Sharma',
                'rating' => 5,
                'quote' => 'Absolutely thrilled with the impeccable service provided by Tectignis! As a small business owner in Navi Mumbai, finding a reliable web development company was paramount. Tectignis exceeded all expectations with their expertise and attention to detail.',
                'sort_order' => 4,
                'is_active' => true,
            ],
        ];

        foreach ($testimonials as $testimonial) {
            Testimonial::updateOrCreate(['name' => $testimonial['name']], $testimonial);
        }
    }
}
