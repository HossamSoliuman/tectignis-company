<?php

namespace Database\Seeders;

use App\Models\CaseStudy;
use App\Models\Product;
use Illuminate\Database\Seeder;

/**
 * The SaaS products Tectignis builds and runs itself. A product is only
 * created when its name is missing, so admin edits are never overwritten.
 */
class ProductSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->products() as $index => $product) {
            if (Product::where('name', $product['name'])->exists()) {
                continue;
            }

            Product::create([
                ...collect($product)->except('case_study')->all(),
                'case_study_id' => $product['case_study'] ? CaseStudy::where('slug', $product['case_study'])->value('id') : null,
                'sort_order' => $index + 1,
                'is_active' => true,
            ]);
        }
    }

    /**
     * @return list<array{name: string, description: string, features: list<string>, icon: string, url: string, case_study: ?string}>
     */
    public function products(): array
    {
        return [
            [
                'name' => 'TenderSet',
                'description' => 'TenderSet gathers open government tenders from GeM, CPPP and state e-procurement portals into one search, matches them to what your company sells and tracks every bid from shortlist to submission.',
                'features' => [
                    'Tenders from GeM, CPPP and state portals',
                    'Email and WhatsApp alerts for matched tenders',
                    'Bid pipeline with approvals and AI eligibility checks',
                ],
                'icon' => 'fas fa-file-contract',
                'url' => 'https://tenderset.in',
                'case_study' => 'tenderset-government-tender-platform',
            ],
            [
                'name' => 'Tectignis Accounting',
                'description' => 'Accounting for Indian businesses that sell, install and service equipment. It takes a quotation through to the invoice and GSTR-1, keeps stock with serial numbers, jobs and AMC contracts, and posts everything to a double-entry ledger.',
                'features' => [
                    'Quotations, invoices, bills and GST reports',
                    'Stock with serial numbers, jobs and AMC contracts',
                    'Bank reconciliation and Tally export for your CA',
                ],
                'icon' => 'fas fa-calculator',
                'url' => 'https://accounting.tectignis.in',
                'case_study' => null,
            ],
            [
                'name' => 'Tectignis QR',
                'description' => 'Create QR codes for links, WiFi, contacts, menus, PDFs and more, add your colours and logo, and download PNG, SVG or PDF. Free without sign-up or watermark, with a Pro plan for dynamic codes and scan statistics.',
                'features' => [
                    'More than 20 QR types and hosted pages',
                    'Custom colours, logo and frames',
                    'Dynamic codes and scan statistics on Pro',
                ],
                'icon' => 'fas fa-qrcode',
                'url' => 'https://qr.tectignis.in',
                'case_study' => 'tectignis-qr-code-generator',
            ],
        ];
    }
}
