# BIR Receipt Plugin

A comprehensive BIR-accredited receipt generation plugin for Laravel POS systems, specifically designed to comply with BIR RMC No. 77-2024 Annex A1-B6.

## Features

### 🧾 BIR Compliance
- **12 BIR Templates**: Complete implementation of A1-A6 and B1-B6 templates
- **Automatic Receipt Numbering**: BIR-compliant receipt number generation
- **Tax Compliance**: Proper VAT calculation and display
- **RMC No. 77-2024**: Full compliance with latest BIR regulations

### 🎨 Customization
- **Visual Editor**: Real-time template customization
- **Form-based Settings**: Easy configuration interface
- **Custom Fields**: Add business-specific information
- **Logo Support**: Upload and display business logos
- **CSS/JS Customization**: Advanced styling options

### 🔧 Integration
- **UltimatePOS Ready**: Seamless integration with UltimatePOS
- **Modular Design**: Easy to integrate with other POS systems
- **API Support**: RESTful API for external integrations
- **Multiple Formats**: HTML, PDF, and Print output

### 📱 User Experience
- **One-Click Generation**: Generate BIR receipts with a single click
- **Template Selection**: Choose from 12 different BIR templates
- **Live Preview**: Real-time preview of customizations
- **Print Ready**: Optimized for thermal and standard printers

## Installation

### 1. Copy Plugin Files
Copy the `BIRReceiptPlugin` folder to your Laravel project's `Modules` directory.

### 2. Register the Module
Add the plugin to your `modules_statuses.json` file:

```json
{
    "BIRReceiptPlugin": true
}
```

### 3. Run Migrations
```bash
php artisan migrate
```

### 4. Seed Default Data
```bash
php artisan db:seed --class="Modules\\BIRReceiptPlugin\\Database\\Seeders\\BIRReceiptTemplateSeeder"
php artisan db:seed --class="Modules\\BIRReceiptPlugin\\Database\\Seeders\\BIRReceiptSettingsSeeder"
```

### 5. Include Integration Script
Add the integration script to your POS interface:

```html
<script src="/Modules/BIRReceiptPlugin/Resources/js/integration.js"></script>
```

## Configuration

### 1. Access Plugin Settings
Navigate to `/bir-receipt/settings` to configure:

- **Business Information**: TIN, name, address, contact details
- **Receipt Settings**: Font size, paper width, margins
- **Default Template**: Choose default BIR template
- **Custom Fields**: Add business-specific information

### 2. Customize Templates
Visit `/bir-receipt/customize/{template_code}` to customize:

- **Layout Settings**: Header/footer height, logo size
- **Field Settings**: Show/hide specific fields
- **Style Settings**: Colors, borders, fonts
- **Custom CSS/JS**: Advanced customization

## Usage

### Generate BIR Receipt

#### From POS Interface
1. Complete a sale transaction
2. Click the "BIR Receipt" button
3. Select template and format
4. Generate receipt

#### Via API
```javascript
POST /bir-receipt/generate
{
    "transaction_id": "TXN-001",
    "template_code": "A1",
    "format": "pdf",
    "customer_name": "John Doe",
    "items": [
        {
            "name": "Product 1",
            "quantity": 2,
            "price": 100.00,
            "total": 200.00
        }
    ],
    "subtotal": 200.00,
    "vat_amount": 24.00,
    "total_amount": 224.00
}
```

### Available Templates

| Code | Name | Description |
|------|------|-------------|
| A1 | Official Receipt (OR) | For goods and services |
| A2 | Sales Invoice (SI) | For goods and services |
| A3 | Cash Invoice (CI) | For goods and services |
| A4 | Charge Invoice (ChI) | For goods and services |
| A5 | Credit Memo (CM) | For goods and services |
| A6 | Debit Memo (DM) | For goods and services |
| B1 | Official Receipt (OR) - Service | For services only |
| B2 | Sales Invoice (SI) - Service | For services only |
| B3 | Cash Invoice (CI) - Service | For services only |
| B4 | Charge Invoice (ChI) - Service | For services only |
| B5 | Credit Memo (CM) - Service | For services only |
| B6 | Debit Memo (DM) - Service | For services only |

## API Endpoints

### Receipt Generation
- `POST /bir-receipt/generate` - Generate BIR receipt
- `GET /bir-receipt/templates` - Get available templates
- `POST /bir-receipt/generate-number` - Generate BIR receipt number

### Settings
- `GET /bir-receipt/settings` - Get current settings
- `POST /bir-receipt/settings/save` - Save settings

### Customization
- `GET /bir-receipt/customize/{template_code}` - Get customization form
- `POST /bir-receipt/customize/{template_code}/save` - Save customization

## Integration with Other POS Systems

### 1. Include Plugin Files
Copy the plugin to your Laravel project's `Modules` directory.

### 2. Register Service Provider
Add to `config/app.php`:
```php
'providers' => [
    // ...
    Modules\BIRReceiptPlugin\Providers\BIRReceiptPluginServiceProvider::class,
],
```

### 3. Add Routes
Include the plugin routes in your `routes/web.php`:
```php
require_once base_path('Modules/BIRReceiptPlugin/Routes/web.php');
```

### 4. Include Integration Script
Add the JavaScript integration file to your POS interface.

## Customization Examples

### Custom CSS
```css
.business-name {
    font-size: 18px;
    color: #2c3e50;
    font-weight: bold;
}

.receipt-title {
    background: #3498db;
    color: white;
    padding: 10px;
    text-align: center;
}
```

### Custom JavaScript
```javascript
// Add custom functionality
document.addEventListener('birReceiptGenerated', function(event) {
    console.log('BIR Receipt generated:', event.detail);
    // Add your custom logic here
});
```

## Troubleshooting

### Common Issues

1. **Plugin not loading**
   - Check if module is registered in `modules_statuses.json`
   - Verify service provider is registered
   - Clear Laravel cache: `php artisan cache:clear`

2. **Templates not showing**
   - Run the template seeder
   - Check database connection
   - Verify template files exist

3. **Integration not working**
   - Include the integration JavaScript file
   - Check browser console for errors
   - Verify CSRF token is included

4. **PDF generation fails**
   - Install DomPDF: `composer require barryvdh/laravel-dompdf`
   - Check file permissions
   - Verify template syntax

## Support

For support and questions:
- Check the plugin documentation
- Review BIR RMC No. 77-2024 for compliance requirements
- Test with sample data before production use

## License

This plugin is provided as-is for educational and development purposes. Ensure compliance with local tax regulations and BIR requirements.

## Changelog

### Version 1.0.0
- Initial release
- 12 BIR-compliant templates
- Customization interface
- UltimatePOS integration
- API support
- PDF generation
