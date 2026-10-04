<?php

namespace App\Enums;

/**
 * The four business pillars the public site is organized around (spec §3).
 * Capabilities are mapped onto a pillar through their `category`, so the
 * header mega-menu, footer and homepage all group services the same way.
 */
enum Pillar: string
{
    case SoftwareSaas = 'software-development';
    case AiAutomation = 'ai-automation';
    case CloudCybersecurity = 'cloud-cybersecurity';
    case ItInfrastructure = 'it-infrastructure';

    public function label(): string
    {
        return match ($this) {
            self::SoftwareSaas => 'Software & SaaS',
            self::AiAutomation => 'AI & Automation',
            self::CloudCybersecurity => 'Cloud & Cybersecurity',
            self::ItInfrastructure => 'IT Infrastructure & Security',
        };
    }

    /**
     * The commercial message shown under the pillar name.
     */
    public function message(): string
    {
        return match ($this) {
            self::SoftwareSaas => 'Build and modernize business applications.',
            self::AiAutomation => 'Automate processes and introduce practical AI into business operations.',
            self::CloudCybersecurity => 'Modernize infrastructure while improving security and reliability.',
            self::ItInfrastructure => 'Design, deploy and support secure business infrastructure.',
        };
    }

    /**
     * Short list of the primary services in this pillar.
     *
     * @return list<string>
     */
    public function primaryServices(): array
    {
        return match ($this) {
            self::SoftwareSaas => ['Custom Software', 'Web Apps', 'SaaS', 'ERP/CRM', 'Mobile Apps', 'E-commerce'],
            self::AiAutomation => ['Generative AI', 'AI Agents', 'OCR', 'Chatbots', 'Business Automation', 'AI Integration'],
            self::CloudCybersecurity => ['AWS', 'Azure', 'GCP', 'Cloud Migration', 'DevOps', 'VAPT', 'Firewall'],
            self::ItInfrastructure => ['Networking', 'CCTV', 'Access Control', 'Servers & Storage', 'Structured Cabling', 'AMC'],
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::SoftwareSaas => 'fas fa-code',
            self::AiAutomation => 'fas fa-robot',
            self::CloudCybersecurity => 'fas fa-shield-alt',
            self::ItInfrastructure => 'fas fa-server',
        };
    }

    /**
     * Capability `category` values that belong to this pillar.
     *
     * @return list<string>
     */
    public function capabilityCategories(): array
    {
        return match ($this) {
            self::SoftwareSaas => ['software_development', 'business_application'],
            self::AiAutomation => ['ai_automation'],
            self::CloudCybersecurity => ['cloud_security'],
            self::ItInfrastructure => ['infrastructure_surveillance'],
        };
    }

    /**
     * Slug of the capability page that represents this pillar until the
     * dedicated `/solutions/{pillar}` pages ship in Phase 4.
     */
    public function primaryCapabilitySlug(): string
    {
        return match ($this) {
            self::SoftwareSaas => 'software-development',
            self::AiAutomation => 'ai-automation',
            self::CloudCybersecurity => 'cloud-security',
            self::ItInfrastructure => 'infrastructure-surveillance',
        };
    }

    public function url(): string
    {
        return route('capabilities.show', $this->primaryCapabilitySlug());
    }

    public static function forCapabilityCategory(?string $category): ?self
    {
        foreach (self::cases() as $pillar) {
            if (in_array($category, $pillar->capabilityCategories(), true)) {
                return $pillar;
            }
        }

        return null;
    }
}
