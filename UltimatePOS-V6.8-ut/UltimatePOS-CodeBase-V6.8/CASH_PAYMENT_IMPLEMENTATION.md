# Cash Payment Implementation with Amount Received and Change Calculation

This document explains the new cash payment functionality that has been implemented in the UltimatePOS system to handle cash payments with amount received and change calculation, similar to the CHOWKING receipt format.

## Features Added

### 1. Cash Amount Received Field
- A new input field appears when "Cash" is selected as the payment method
- Allows cashiers to enter the exact amount received from customers
- Automatically calculates and displays change when amount received is greater than the payment amount

### 2. Change Calculation
- Real-time calculation of change (amount received - payment amount)
- Change is automatically displayed in the payment modal
- Change return is properly recorded in the system

### 3. New Receipt Template
- CHOWKING Style receipt template that displays:
  - Amount received from customer
  - Change given to customer
  - Professional receipt layout similar to restaurant receipts

## Database Changes

### New Migration Files Created:
1. `2025_01_09_000000_add_cash_amount_received_to_transaction_payments_table.php`
   - Adds `cash_amount_received` field to `transaction_payments` table
   
2. `2025_01_09_000001_update_invoice_layouts_design_enum.php`
   - Updates the design enum to include `chowking_style` option

### Database Schema Changes:
- `transaction_payments` table: New field `cash_amount_received` (decimal 20,4, nullable)
- `invoice_layouts` table: Design enum updated to include `chowking_style`

## Files Modified

### 1. Payment Form (`resources/views/sale_pos/partials/payment_row_form.blade.php`)
- Added cash amount received input field
- Field visibility controlled by payment method selection

### 2. POS JavaScript (`public/js/pos.js`)
- Added payment method change handler for cash amount received field
- Added real-time change calculation
- Auto-fills amount received with payment amount when cash is selected

### 3. Transaction Utility (`app/Utils/TransactionUtil.php`)
- Modified `createOrUpdatePaymentLines()` to handle cash_amount_received
- Updated `getReceiptDetails()` to include cash amount received and change in receipt data

### 4. Invoice Layout Controller (`app/Http/Controllers/InvoiceLayoutController.php`)
- Added `chowking_style` to available design options

### 5. Language Files (`lang/en/lang_v1.php`)
- Added new language strings:
  - `amount_received` => "Amount Received"
  - `cash_change` => "Change"

## New Receipt Template

### File: `resources/views/sale_pos/receipts/chowking_style.blade.php`
- Professional restaurant-style receipt layout
- Displays business information prominently
- Shows items with quantities and prices
- Includes cash payment details with amount received and change
- VAT details section
- Customer information fields (blank for manual entry)
- Feedback and contact information
- Professional footer with business details

## How to Use

### 1. Setup
1. Run the database migrations:
   ```bash
   php artisan migrate
   ```

2. Create a new invoice layout or edit existing one:
   - Go to Settings > Invoice Layouts
   - Select "CHOWKING Style" as the design
   - Configure other settings as needed

3. Assign the layout to your business location:
   - Go to Settings > Business Locations
   - Select the invoice layout for your location

### 2. Using Cash Payments
1. In the POS screen, add items to the sale
2. Click on "Payment" button
3. Select "Cash" as payment method
4. Enter the payment amount
5. Enter the amount received from customer
6. The system will automatically calculate and display change
7. Complete the sale

### 3. Receipt Printing
- When using the CHOWKING Style template, receipts will show:
  - Amount received from customer
  - Change given to customer
  - Professional restaurant-style layout

## Technical Details

### JavaScript Functions Added:
- Payment method change handler for cash amount received field
- Real-time change calculation
- Auto-fill amount received functionality

### PHP Changes:
- New field handling in payment processing
- Receipt data preparation for cash payments
- Database field storage and retrieval

### Template Changes:
- New receipt template with cash payment details
- Responsive design for various printer types
- Professional styling and layout

## Benefits

1. **Professional Receipts**: Restaurant-style receipts that look professional
2. **Accurate Change Calculation**: Eliminates manual change calculation errors
3. **Better Customer Service**: Clear display of amount received and change
4. **Audit Trail**: Proper recording of cash transactions with amounts received
5. **Flexibility**: Works with existing POS workflow

## Compatibility

- Compatible with existing UltimatePOS installations
- No breaking changes to existing functionality
- Backward compatible with existing payment methods
- Works with all printer types (thermal, normal, etc.)

## Troubleshooting

### Common Issues:
1. **Field not visible**: Ensure "Cash" is selected as payment method
2. **Change not calculating**: Check that amount received is greater than payment amount
3. **Template not showing**: Verify invoice layout is set to "CHOWKING Style"

### Debug Steps:
1. Check browser console for JavaScript errors
2. Verify database migrations ran successfully
3. Confirm invoice layout settings
4. Check payment method selection

## Future Enhancements

Potential improvements that could be added:
1. Multiple currency support for cash denominations
2. Receipt customization options
3. Additional payment method details
4. Enhanced reporting for cash transactions
5. Integration with cash drawer systems

## Support

For technical support or questions about this implementation, please refer to the UltimatePOS documentation or contact the development team.
