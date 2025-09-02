# Custom Receipt Plugin for UltimatePOS

This plugin adds custom thermal receipt generation functionality to UltimatePOS v6.8, similar to restaurant receipts like Chowking.

## Features

- **Thermal Receipt Template**: Generates receipts that look like traditional thermal printer receipts
- **Restaurant-style Layout**: Includes dine-in indicators, table numbers, and restaurant-specific formatting
- **VAT Breakdown**: Shows detailed VAT information as required by tax regulations
- **QR Code Support**: Placeholder for QR codes (can be customized)
- **PDF Generation**: Generate PDF receipts for printing or sharing
- **Print Support**: Direct printing functionality

## Installation

1. **Module Structure**: The module is already created in the `Modules/CustomReceipt` directory
2. **Enable Module**: The module is automatically enabled in `modules_statuses.json`
3. **Clear Cache**: Run the following commands to clear application cache:

```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

## Usage

### Method 1: Direct Access
Access the custom receipt generator directly:
- **URL**: `http://your-domain/custom-receipt`
- This shows a list of all sales transactions with options to generate custom receipts

### Method 2: Integration with Existing POS
Add custom receipt buttons to existing POS views by including the helper functions:

```php
// In your blade template
<a href="{{ get_custom_receipt_url($transaction->id) }}" 
   class="btn btn-primary" target="_blank">
    <i class="fa fa-print"></i> Custom Receipt
</a>
```

### Method 3: API Integration
Use the helper functions in your controllers:

```php
// Generate custom receipt HTML
$receipt_html = generate_custom_thermal_receipt($transaction_id);

// Get receipt URLs
$pdf_url = get_custom_receipt_url($transaction_id);
$print_url = get_custom_receipt_print_url($transaction_id);
```

## Customization

### Receipt Template
The main receipt template is located at:
`resources/views/sale_pos/receipts/thermal_restaurant.blade.php`

You can customize:
- Business information layout
- Item display format
- VAT breakdown sections
- Footer information
- QR code placement

### Styling
The template uses CSS for thermal printer styling:
- Monospace font (Courier New)
- Narrow width (300px max)
- Dashed separators
- Right-aligned prices

### Business Information
The receipt automatically pulls business information from:
- Business name and address
- Tax registration numbers
- Contact information
- Location-specific settings

## Routes

The plugin adds the following routes:
- `GET /custom-receipt` - List all transactions
- `GET /custom-receipt/generate/{id}` - Generate PDF receipt
- `GET /custom-receipt/print/{id}` - Print receipt view

## Requirements

- UltimatePOS v6.8
- PHP 8.1+
- Laravel 9.x
- DomPDF package (already included in UltimatePOS)

## Troubleshooting

### Module Not Loading
1. Check if the module is enabled in `modules_statuses.json`
2. Clear application cache
3. Check file permissions

### Receipt Not Generating
1. Ensure the transaction exists and belongs to the current business
2. Check if user has proper permissions
3. Verify the receipt template file exists

### Styling Issues
1. Check if CSS is loading properly
2. Ensure the thermal receipt template is in the correct location
3. Clear view cache if template changes are not reflecting

## Support

For issues or customization requests, please refer to the UltimatePOS documentation or contact the development team.

## License

This plugin follows the same license as UltimatePOS.

