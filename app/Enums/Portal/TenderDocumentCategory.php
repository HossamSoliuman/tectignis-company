<?php

namespace App\Enums\Portal;

/**
 * The seven document groups a bid is assembled from (§10).
 */
enum TenderDocumentCategory: string
{
    case Company = 'company';
    case Financial = 'financial';
    case Technical = 'technical';
    case Oem = 'oem';
    case BidLegal = 'bid_legal';
    case Commercial = 'commercial';
    case Submission = 'submission';

    public function label(): string
    {
        return match ($this) {
            self::Company => 'Company',
            self::Financial => 'Financial',
            self::Technical => 'Technical',
            self::Oem => 'OEM',
            self::BidLegal => 'Bid & Legal',
            self::Commercial => 'Commercial',
            self::Submission => 'Submission',
        };
    }

    public function badgeColor(): string
    {
        return match ($this) {
            self::Company => 'slate',
            self::Financial => 'emerald',
            self::Technical => 'sky',
            self::Oem => 'violet',
            self::BidLegal => 'indigo',
            self::Commercial => 'amber',
            self::Submission => 'rose',
        };
    }

    /**
     * Categories shown on the Technical tab of the workspace.
     *
     * @return array<int, self>
     */
    public static function technical(): array
    {
        return [self::Technical, self::Oem];
    }

    /**
     * Categories shown on the Commercial tab of the workspace.
     *
     * @return array<int, self>
     */
    public static function commercial(): array
    {
        return [self::Commercial, self::Financial];
    }

    /**
     * @return array<string, string>
     */
    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $category): array => [$category->value => $category->label()])
            ->all();
    }
}
