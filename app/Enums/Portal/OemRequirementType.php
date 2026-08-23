<?php

namespace App\Enums\Portal;

/**
 * What is being chased from an OEM (§11).
 */
enum OemRequirementType: string
{
    case Maf = 'maf';
    case Authorization = 'authorization';
    case Quotation = 'quotation';
    case Datasheet = 'datasheet';
    case Certificate = 'certificate';
    case Other = 'other';

    public function label(): string
    {
        return match ($this) {
            self::Maf => 'MAF',
            self::Authorization => 'Authorization Letter',
            self::Quotation => 'Quotation',
            self::Datasheet => 'Datasheet',
            self::Certificate => 'Certificate',
            self::Other => 'Other',
        };
    }

    /**
     * @return array<string, string>
     */
    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $type): array => [$type->value => $type->label()])
            ->all();
    }
}
