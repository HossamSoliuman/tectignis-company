<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'slug' => 'website-design-development',
                'title' => 'Website Design & Development',
                'icon' => 'website-development-com.webp',
                'category' => 'digital_services',
                'short_description' => 'We craft responsive, aesthetically pleasing websites that not only reflect your brand identity but also offer seamless user experiences.',
                'seo_title' => 'Website Design & Development Services in Navi Mumbai | Custom Websites Worldwide',
                'seo_description' => 'We are leading professional website design & development services in Navi Mumbai & worldwide. Build responsive, SEO-friendly, and user-focused websites today.',
                'sort_order' => 1,
            ],
            [
                'slug' => 'web-application-development',
                'title' => 'Web Application Development',
                'icon' => 'web-app-development1.webp',
                'category' => 'digital_services',
                'short_description' => 'Our expert team develops powerful, secure, and intuitive web applications that streamline your business operations and improve overall efficiency.',
                'seo_title' => 'Web Application Development Services in Navi Mumbai',
                'seo_description' => 'Expert web application development services in Navi Mumbai. We build powerful, secure, and scalable web apps for businesses.',
                'sort_order' => 2,
            ],
            [
                'slug' => 'ecommerce-development',
                'title' => 'E-commerce Development',
                'icon' => 'ecommerce-development.webp',
                'category' => 'digital_services',
                'short_description' => 'We deliver customized e-commerce solutions that help you build, launch, and grow your online business with ease of use and a smooth shopping experience.',
                'seo_title' => 'E-commerce Development Services in Navi Mumbai',
                'seo_description' => 'Build your online store with Tectignis. Custom e-commerce solutions for startups and enterprises in Navi Mumbai & India.',
                'sort_order' => 3,
            ],
            [
                'slug' => 'mobile-app-development',
                'title' => 'Mobile App Development',
                'icon' => 'mobile-app-development.webp',
                'category' => 'digital_services',
                'short_description' => 'We specialize in creating high-quality native mobile applications for both Android and iOS platforms, tailored to your business needs.',
                'seo_title' => 'Mobile App Development Services in Navi Mumbai',
                'seo_description' => 'Android and iOS mobile app development in Navi Mumbai. Native and hybrid apps built by expert developers.',
                'sort_order' => 4,
            ],
            [
                'slug' => 'hybrid-app-development',
                'title' => 'Hybrid App Development',
                'icon' => 'hybrid-app-development2.webp',
                'category' => 'digital_services',
                'short_description' => 'Our hybrid app development services offer the flexibility of cross-platform applications, delivering faster time-to-market and cost-effective solutions.',
                'seo_title' => 'Hybrid App Development Services in Navi Mumbai',
                'seo_description' => 'Cross-platform hybrid app development in Navi Mumbai. Fast, cost-effective apps for Android & iOS.',
                'sort_order' => 5,
            ],
            [
                'slug' => 'custom-software-development',
                'title' => 'Custom Software Development',
                'icon' => 'Customize-Software-Development1.webp',
                'category' => 'digital_services',
                'short_description' => 'We design and develop tailored software solutions that address your unique business challenges, enhancing productivity and competitiveness.',
                'seo_title' => 'Custom Software Development Services in Navi Mumbai',
                'seo_description' => 'Customized software development in Navi Mumbai. ERP, CRM, and enterprise-grade software built to your requirements.',
                'sort_order' => 6,
            ],
            [
                'slug' => 'digital-marketing',
                'title' => 'Digital Marketing Services',
                'icon' => 'digital-marketing-com.webp',
                'category' => 'digital_services',
                'short_description' => 'Our digital marketing services include Social Media Marketing, SEO, and PPC advertising strategies that help you increase visibility and drive traffic.',
                'seo_title' => 'Digital Marketing Services in Navi Mumbai',
                'seo_description' => 'Result-oriented digital marketing in Navi Mumbai. SEO, social media, PPC and content marketing to grow your business.',
                'sort_order' => 7,
            ],
            [
                'slug' => 'cctv-security',
                'title' => 'CCTV & Security Solutions',
                'icon' => 'cctv-com.webp',
                'category' => 'it_services',
                'short_description' => 'We provide state-of-the-art CCTV systems and security solutions to protect your business premises, ensuring safety and integrity of your operations.',
                'seo_title' => 'CCTV Installation Services in Navi Mumbai | Tectignis IT Solutions',
                'seo_description' => 'Secure your business and home with advanced CCTV & security solutions in Navi Mumbai & across India. Expert installation, monitoring & maintenance.',
                'sort_order' => 8,
            ],
            [
                'slug' => 'access-control',
                'title' => 'Access Control Systems',
                'icon' => 'access-control-system.webp',
                'category' => 'it_services',
                'short_description' => 'We implement advanced access control systems that provide secure entry management, protecting your physical and digital assets with modern technology.',
                'seo_title' => 'Access Control Systems in Navi Mumbai',
                'seo_description' => 'Advanced access control systems in Navi Mumbai. Biometric, card-based and smart entry management solutions.',
                'sort_order' => 9,
            ],
            [
                'slug' => 'amc',
                'title' => 'Annual Maintenance Contracts (AMC)',
                'icon' => 'annual-maintenance-com.webp',
                'category' => 'it_services',
                'short_description' => 'Our AMC services offer proactive support and maintenance, ensuring your IT infrastructure remains optimized, secure and running at peak performance.',
                'seo_title' => 'Annual Maintenance Contracts (AMC) in Navi Mumbai',
                'seo_description' => 'Comprehensive hardware & software AMC services in Navi Mumbai. Keep your IT systems running with proactive maintenance.',
                'sort_order' => 10,
            ],
            [
                'slug' => 'software-licensing',
                'title' => 'Software Licensing',
                'icon' => 'software-licensing.webp',
                'category' => 'it_services',
                'short_description' => 'We assist with managing your software licenses, ensuring compliance and reducing the risk of legal or operational issues related to software usage.',
                'seo_title' => 'Software Licensing Services in Navi Mumbai',
                'seo_description' => 'Software licensing management in Navi Mumbai. Ensure compliance and optimize your software asset management.',
                'sort_order' => 11,
            ],
            [
                'slug' => 'network-storage',
                'title' => 'Network Storage Solutions',
                'icon' => 'Network-Storage-Solutions.webp',
                'category' => 'it_services',
                'short_description' => 'Our tailored network storage solutions are designed to help businesses securely manage and store data with high availability and performance.',
                'seo_title' => 'Network Storage Solutions in Navi Mumbai',
                'seo_description' => 'NAS and SAN network storage solutions in Navi Mumbai. Secure, scalable data storage for businesses.',
                'sort_order' => 12,
            ],
            [
                'slug' => 'workstation-solutions',
                'title' => 'Workstation Solutions',
                'icon' => 'workstation-solutions.webp',
                'category' => 'it_services',
                'short_description' => 'We provide high-performance workstations customized for various business needs, ensuring your team has the right tools for peak productivity.',
                'seo_title' => 'Workstation Solutions in Navi Mumbai',
                'seo_description' => 'High-performance workstation solutions in Navi Mumbai. Custom-configured desktops and laptops for your business needs.',
                'sort_order' => 13,
            ],
            [
                'slug' => 'networking-solutions',
                'title' => 'Networking Solutions',
                'icon' => 'network-solutions-com.webp',
                'category' => 'it_services',
                'short_description' => 'From secure infrastructure setup to network management, our networking solutions ensure reliable and efficient connectivity across your organization.',
                'seo_title' => 'Networking Solutions in Navi Mumbai',
                'seo_description' => 'Enterprise networking solutions in Navi Mumbai. Structured cabling, switches, routers and network management services.',
                'sort_order' => 14,
            ],
        ];

        foreach ($services as $service) {
            Service::updateOrCreate(['slug' => $service['slug']], array_merge($service, ['is_active' => true]));
        }
    }
}
