<?php

use App\Models\Product;
use App\Models\Setting;
use Illuminate\Database\Migrations\Migration;

/**
 * Replaces the screenshots copied from the case studies with current ones
 * of TenderSet, Tectignis Accounting and Tectignis QR, and adds each
 * product's short tour video. Screenshots and videos added in the admin
 * are kept, after the new ones.
 */
return new class extends Migration
{
    /**
     * @var array<string, array{screenshots: list<array{image: string, alt: string}>, video: string}>
     */
    private const MEDIA = [
        'TenderSet' => [
            'screenshots' => [
                ['image' => 'products/tenderset-home.webp', 'alt' => 'TenderSet home page with one search across every government tender portal'],
                ['image' => 'products/tenderset-live-tenders.webp', 'alt' => 'TenderSet live tenders with filters by sector, category, state, portal and dates'],
                ['image' => 'products/tenderset-tender-details.webp', 'alt' => 'A tender on TenderSet with its buyer, value, EMD, fees and dates'],
                ['image' => 'products/tenderset-browse.webp', 'alt' => 'TenderSet tenders by sector, state, buyer and portal'],
                ['image' => 'products/tenderset-features.webp', 'alt' => 'TenderSet features for finding tenders, alerts, the bid pipeline and AI document checks'],
                ['image' => 'products/tenderset-pricing.webp', 'alt' => 'TenderSet Starter, Team and Business plans'],
            ],
            'video' => 'products/tenderset-tour.mp4',
        ],
        'Tectignis Accounting' => [
            'screenshots' => [
                ['image' => 'products/accounting-home.webp', 'alt' => 'Tectignis Accounting home page with a tax invoice and its balanced journal'],
                ['image' => 'products/accounting-sales-flow.webp', 'alt' => 'Quotation, sales order, delivery challan, tax invoice and receipt in Tectignis Accounting'],
                ['image' => 'products/accounting-modules.webp', 'alt' => 'Tectignis Accounting modules for sales, purchases, inventory, projects, service and AMC, and banking'],
                ['image' => 'products/accounting-for-accountants.webp', 'alt' => 'Reports a chartered accountant opens in Tectignis Accounting, from GSTR-1 to the Tally export'],
                ['image' => 'products/accounting-pricing.webp', 'alt' => 'Tectignis Accounting Starter, Growth and Business plans'],
            ],
            'video' => 'products/accounting-tour.mp4',
        ],
        'Tectignis QR' => [
            'screenshots' => [
                ['image' => 'products/qr-design.webp', 'alt' => 'Designing a QR code in Tectignis QR with ready-made templates'],
                ['image' => 'products/qr-code-types.webp', 'alt' => 'Tectignis QR code types, from links and WiFi to menus and PDFs'],
                ['image' => 'products/qr-add-content.webp', 'alt' => 'Adding a website link in Tectignis QR with a live preview of the code'],
                ['image' => 'products/qr-wifi.webp', 'alt' => 'A WiFi QR code with the network name and password in Tectignis QR'],
                ['image' => 'products/qr-pricing.webp', 'alt' => 'Tectignis QR Free, Starter, Pro and Business plans'],
            ],
            'video' => 'products/qr-tour.mp4',
        ],
    ];

    public function up(): void
    {
        foreach (self::MEDIA as $name => $media) {
            $product = Product::where('name', $name)->first();

            if (! $product) {
                continue;
            }

            $newImages = array_column($media['screenshots'], 'image');
            $kept = [];

            foreach ($product->galleryImages() as $item) {
                if (in_array($item['image'], $newImages, true)) {
                    continue;
                }

                // Copies of the case study screenshots are replaced.
                if (is_file(public_path('uploads/case-studies/'.basename($item['image'])))) {
                    continue;
                }

                $kept[] = $item;
            }

            $videos = $product->videos ?? [];

            if (! in_array($media['video'], array_column($videos, 'source'), true)) {
                array_unshift($videos, ['source' => $media['video'], 'title' => null]);
            }

            $product->update(['gallery' => [...$media['screenshots'], ...$kept], 'videos' => $videos]);
        }

        Setting::flushSiteCache();
    }

    public function down(): void
    {
        foreach (self::MEDIA as $name => $media) {
            $product = Product::where('name', $name)->first();

            if (! $product) {
                continue;
            }

            $newImages = array_column($media['screenshots'], 'image');

            $product->update([
                'gallery' => array_values(array_filter($product->gallery ?? [], fn (array $item): bool => ! in_array($item['image'] ?? null, $newImages, true))),
                'videos' => array_values(array_filter($product->videos ?? [], fn (array $video): bool => ($video['source'] ?? null) !== $media['video'])),
            ]);
        }

        Setting::flushSiteCache();
    }
};
