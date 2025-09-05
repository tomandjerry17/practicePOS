<?php

namespace Modules\BIRReceiptPlugin\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\BIRReceiptPlugin\Models\BIRReceiptTemplate;

class BIRReceiptTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $templates = [
            [
                'template_code' => 'A1',
                'template_name' => 'Official Receipt (OR)',
                'description' => 'Official Receipt for goods and services - BIR RMC No. 77-2024 Annex A1',
                'template_content' => 'a1',
                'template_settings' => [
                    'type' => 'goods',
                    'payment_type' => 'any',
                    'show_customer_info' => true,
                    'show_payment_method' => false,
                ],
                'is_active' => true,
                'is_default' => true,
            ],
            [
                'template_code' => 'A2',
                'template_name' => 'Sales Invoice (SI)',
                'description' => 'Sales Invoice for goods and services - BIR RMC No. 77-2024 Annex A2',
                'template_content' => 'a2',
                'template_settings' => [
                    'type' => 'goods',
                    'payment_type' => 'credit',
                    'show_customer_info' => true,
                    'show_payment_method' => false,
                ],
                'is_active' => true,
                'is_default' => false,
            ],
            [
                'template_code' => 'A3',
                'template_name' => 'Cash Invoice (CI)',
                'description' => 'Cash Invoice for goods and services - BIR RMC No. 77-2024 Annex A3',
                'template_content' => 'a3',
                'template_settings' => [
                    'type' => 'goods',
                    'payment_type' => 'cash',
                    'show_customer_info' => true,
                    'show_payment_method' => true,
                ],
                'is_active' => true,
                'is_default' => false,
            ],
            [
                'template_code' => 'A4',
                'template_name' => 'Charge Invoice (ChI)',
                'description' => 'Charge Invoice for goods and services - BIR RMC No. 77-2024 Annex A4',
                'template_content' => 'a1', // Using A1 template for now
                'template_settings' => [
                    'type' => 'goods',
                    'payment_type' => 'charge',
                    'show_customer_info' => true,
                    'show_payment_method' => true,
                ],
                'is_active' => true,
                'is_default' => false,
            ],
            [
                'template_code' => 'A5',
                'template_name' => 'Credit Memo (CM)',
                'description' => 'Credit Memo for goods and services - BIR RMC No. 77-2024 Annex A5',
                'template_content' => 'a1', // Using A1 template for now
                'template_settings' => [
                    'type' => 'goods',
                    'payment_type' => 'credit_memo',
                    'show_customer_info' => true,
                    'show_payment_method' => false,
                ],
                'is_active' => true,
                'is_default' => false,
            ],
            [
                'template_code' => 'A6',
                'template_name' => 'Debit Memo (DM)',
                'description' => 'Debit Memo for goods and services - BIR RMC No. 77-2024 Annex A6',
                'template_content' => 'a1', // Using A1 template for now
                'template_settings' => [
                    'type' => 'goods',
                    'payment_type' => 'debit_memo',
                    'show_customer_info' => true,
                    'show_payment_method' => false,
                ],
                'is_active' => true,
                'is_default' => false,
            ],
            [
                'template_code' => 'B1',
                'template_name' => 'Official Receipt (OR) - Service',
                'description' => 'Official Receipt for services - BIR RMC No. 77-2024 Annex B1',
                'template_content' => 'a1', // Using A1 template for now
                'template_settings' => [
                    'type' => 'service',
                    'payment_type' => 'any',
                    'show_customer_info' => true,
                    'show_payment_method' => false,
                ],
                'is_active' => true,
                'is_default' => false,
            ],
            [
                'template_code' => 'B2',
                'template_name' => 'Sales Invoice (SI) - Service',
                'description' => 'Sales Invoice for services - BIR RMC No. 77-2024 Annex B2',
                'template_content' => 'a2', // Using A2 template for now
                'template_settings' => [
                    'type' => 'service',
                    'payment_type' => 'credit',
                    'show_customer_info' => true,
                    'show_payment_method' => false,
                ],
                'is_active' => true,
                'is_default' => false,
            ],
            [
                'template_code' => 'B3',
                'template_name' => 'Cash Invoice (CI) - Service',
                'description' => 'Cash Invoice for services - BIR RMC No. 77-2024 Annex B3',
                'template_content' => 'a3', // Using A3 template for now
                'template_settings' => [
                    'type' => 'service',
                    'payment_type' => 'cash',
                    'show_customer_info' => true,
                    'show_payment_method' => true,
                ],
                'is_active' => true,
                'is_default' => false,
            ],
            [
                'template_code' => 'B4',
                'template_name' => 'Charge Invoice (ChI) - Service',
                'description' => 'Charge Invoice for services - BIR RMC No. 77-2024 Annex B4',
                'template_content' => 'a1', // Using A1 template for now
                'template_settings' => [
                    'type' => 'service',
                    'payment_type' => 'charge',
                    'show_customer_info' => true,
                    'show_payment_method' => true,
                ],
                'is_active' => true,
                'is_default' => false,
            ],
            [
                'template_code' => 'B5',
                'template_name' => 'Credit Memo (CM) - Service',
                'description' => 'Credit Memo for services - BIR RMC No. 77-2024 Annex B5',
                'template_content' => 'a1', // Using A1 template for now
                'template_settings' => [
                    'type' => 'service',
                    'payment_type' => 'credit_memo',
                    'show_customer_info' => true,
                    'show_payment_method' => false,
                ],
                'is_active' => true,
                'is_default' => false,
            ],
            [
                'template_code' => 'B6',
                'template_name' => 'Debit Memo (DM) - Service',
                'description' => 'Debit Memo for services - BIR RMC No. 77-2024 Annex B6',
                'template_content' => 'a1', // Using A1 template for now
                'template_settings' => [
                    'type' => 'service',
                    'payment_type' => 'debit_memo',
                    'show_customer_info' => true,
                    'show_payment_method' => false,
                ],
                'is_active' => true,
                'is_default' => false,
            ],
        ];

        foreach ($templates as $template) {
            BIRReceiptTemplate::updateOrCreate(
                ['template_code' => $template['template_code']],
                $template
            );
        }
    }
}
