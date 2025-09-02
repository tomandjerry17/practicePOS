<?php

if (!function_exists('generate_custom_thermal_receipt')) {
    /**
     * Generate a custom thermal receipt for a transaction
     *
     * @param int $transaction_id
     * @return string HTML content
     */
    function generate_custom_thermal_receipt($transaction_id)
    {
        $business_id = request()->session()->get('user.business_id');
        
        $transaction = \App\Transaction::where('business_id', $business_id)
            ->where('id', $transaction_id)
            ->with(['sell_lines', 'payment_lines', 'contact', 'business', 'location'])
            ->first();

        if (!$transaction) {
            return '';
        }

        $receipt_details = prepare_custom_receipt_data($transaction);
        
        return view('sale_pos.receipts.thermal_restaurant', compact('receipt_details'))->render();
    }
}

if (!function_exists('prepare_custom_receipt_data')) {
    /**
     * Prepare receipt data for the custom template
     *
     * @param \App\Transaction $transaction
     * @return object
     */
    function prepare_custom_receipt_data($transaction)
    {
        $business = $transaction->business;
        $location = $transaction->location;
        
        $receipt_details = new \stdClass();
        
        // Business information
        $receipt_details->display_name = $business->name;
        $receipt_details->address = $location->address;
        $receipt_details->contact = $location->mobile;
        $receipt_details->tax_info1 = $business->tax_number_1;
        $receipt_details->tax_info2 = $business->tax_number_2;
        
        // Invoice information
        $receipt_details->invoice_no = $transaction->invoice_no;
        $receipt_details->invoice_no_prefix = $location->invoice_no_prefix;
        $receipt_details->invoice_date = date('m/d/Y H:i', strtotime($transaction->transaction_date));
        $receipt_details->invoice_heading = 'SALES INVOICE';
        
        // Service staff
        $receipt_details->service_staff = $transaction->service_staff ? $transaction->service_staff->user_full_name : '';
        
        // Table information
        $receipt_details->table = $transaction->table_no;
        
        // Lines
        $receipt_details->lines = $transaction->sell_lines;
        
        // Payment lines
        $receipt_details->payment_lines = $transaction->payment_lines;
        
        // Totals
        $receipt_details->total_before_tax = $transaction->total_before_tax;
        $receipt_details->tax_amount = $transaction->tax_amount;
        $receipt_details->final_total = $transaction->final_total;
        
        return $receipt_details;
    }
}

if (!function_exists('get_custom_receipt_url')) {
    /**
     * Get the URL for generating custom receipt
     *
     * @param int $transaction_id
     * @return string
     */
    function get_custom_receipt_url($transaction_id)
    {
        return route('custom-receipt.generate', $transaction_id);
    }
}

if (!function_exists('get_custom_receipt_print_url')) {
    /**
     * Get the URL for printing custom receipt
     *
     * @param int $transaction_id
     * @return string
     */
    function get_custom_receipt_print_url($transaction_id)
    {
        return route('custom-receipt.print', $transaction_id);
    }
}
