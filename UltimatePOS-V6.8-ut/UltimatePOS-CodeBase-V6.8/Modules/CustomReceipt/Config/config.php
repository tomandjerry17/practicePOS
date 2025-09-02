<?php

return [
    'name' => 'CustomReceipt',
    'description' => 'Custom Receipt Generator Plugin for UltimatePOS',
    'version' => '1.0.0',
    'author' => 'Your Name',
    'enabled' => true,
    'providers' => [
        'Modules\CustomReceipt\Providers\CustomReceiptServiceProvider',
    ],
    'aliases' => [
        'CustomReceipt' => 'Modules\CustomReceipt\Facades\CustomReceipt',
    ],
    'files' => [
        'start.php' => 'Modules\CustomReceipt\start.php',
    ],
];

