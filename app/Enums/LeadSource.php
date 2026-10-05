<?php

namespace App\Enums;

/**
 * The public form a lead came in through.
 */
enum LeadSource: string
{
    case Contact = 'contact';
    case Consultation = 'consultation';
    case Career = 'career';
    case Newsletter = 'newsletter';
    case Download = 'download';

    public function label(): string
    {
        return match ($this) {
            self::Contact => 'Contact Enquiry',
            self::Consultation => 'Consultation Request',
            self::Career => 'Job Application',
            self::Newsletter => 'Newsletter Subscription',
            self::Download => 'Resource Download',
        };
    }

    /**
     * Admin icon name (see the x-admin.icon component).
     */
    public function icon(): string
    {
        return match ($this) {
            self::Contact => 'envelope',
            self::Consultation => 'chat-alt',
            self::Career => 'briefcase',
            self::Newsletter => 'bell',
            self::Download => 'download',
        };
    }

    /**
     * Project enquiries (contact page, consultation/quote modal, service pages)
     * as opposed to sign-ups, downloads and job applications.
     */
    public function isEnquiry(): bool
    {
        return in_array($this, [self::Contact, self::Consultation], true);
    }

    /**
     * @return array<string, string> value => label, for select inputs.
     */
    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $source): array => [$source->value => $source->label()])
            ->all();
    }
}
