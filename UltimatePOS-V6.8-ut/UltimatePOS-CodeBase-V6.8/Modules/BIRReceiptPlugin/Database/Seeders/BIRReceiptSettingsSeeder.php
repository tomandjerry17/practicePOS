<?php

namespace Modules\BIRReceiptPlugin\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\BIRReceiptPlugin\Models\BIRReceiptSetting;

class BIRReceiptSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        BIRReceiptSetting::updateOrCreate(
            [
                'business_id' => null,
                'is_active' => true,
            ],
            [
                'tin_number' => '000-000-000-000',
                'business_name' => 'Your Business Name',
                'business_address' => 'Your Business Address',
                'business_phone' => '+63 123 456 7890',
                'business_email' => 'info@yourbusiness.com',
                'business_website' => 'https://yourbusiness.com',
                'logo_path' => null,
                'header_text' => 'Thank you for your business!',
                'footer_text' => 'This receipt is issued in compliance with BIR RMC No. 77-2024',
                'default_template' => 'A1',
                'custom_fields' => [
                    'field1' => [
                        'label' => 'License No.',
                        'value' => 'LTO-123456',
                        'position' => 'header'
                    ],
                    'field2' => [
                        'label' => 'Permit No.',
                        'value' => 'PERMIT-789',
                        'position' => 'header'
                    ]
                ],
                'receipt_settings' => [
                    'font_size' => '12',
                    'paper_width' => '80mm',
                    'margin' => '10',
                    'currency_symbol' => '₱',
                    'font_family' => 'Courier New',
                    'line_height' => '1.2',
                ],
            ]
        );
    }
}
