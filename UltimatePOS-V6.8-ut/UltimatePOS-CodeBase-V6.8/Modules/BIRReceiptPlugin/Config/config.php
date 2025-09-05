<?php

return [
    'name' => 'BIR Receipt Plugin',
    'version' => '1.0.0',
    'description' => 'BIR-accredited receipt generation plugin with customizable templates',
    
    // BIR Compliance Settings
    'bir_compliance' => [
        'rmc_number' => 'RMC No. 77-2024',
        'annex_templates' => [
            'A1' => 'Official Receipt (OR)',
            'A2' => 'Sales Invoice (SI)',
            'A3' => 'Cash Invoice (CI)',
            'A4' => 'Charge Invoice (ChI)',
            'A5' => 'Credit Memo (CM)',
            'A6' => 'Debit Memo (DM)',
            'B1' => 'Official Receipt (OR) - Service',
            'B2' => 'Sales Invoice (SI) - Service',
            'B3' => 'Cash Invoice (CI) - Service',
            'B4' => 'Charge Invoice (ChI) - Service',
            'B5' => 'Credit Memo (CM) - Service',
            'B6' => 'Debit Memo (DM) - Service',
        ],
        'required_fields' => [
            'tin' => 'Tax Identification Number',
            'business_name' => 'Business Name',
            'business_address' => 'Business Address',
            'receipt_number' => 'Receipt Number',
            'date' => 'Date of Transaction',
            'customer_name' => 'Customer Name',
            'customer_address' => 'Customer Address',
            'customer_tin' => 'Customer TIN',
            'items' => 'Items/Services',
            'amount' => 'Amount',
            'vat_amount' => 'VAT Amount',
            'total_amount' => 'Total Amount',
        ]
    ],
    
    // Receipt Settings
    'receipt' => [
        'default_template' => 'A1',
        'paper_size' => '80mm', // 80mm thermal paper
        'font_family' => 'Courier New',
        'font_size' => 12,
        'margin' => 10,
        'max_width' => 300, // pixels
    ],
    
    // Customization Settings
    'customization' => [
        'allow_logo' => true,
        'allow_header_text' => true,
        'allow_footer_text' => true,
        'allow_custom_fields' => true,
        'allow_layout_modification' => true,
    ],
    
    // Integration Settings
    'integration' => [
        'pos_system' => 'ultimatepos', // Can be changed for other POS systems
        'replace_default_receipt' => true,
        'add_bir_button' => true,
    ],
    
    // Database Settings
    'database' => [
        'prefix' => 'bir_',
        'tables' => [
            'receipt_templates' => 'bir_receipt_templates',
            'receipt_settings' => 'bir_receipt_settings',
            'receipt_customizations' => 'bir_receipt_customizations',
        ]
    ]
];
