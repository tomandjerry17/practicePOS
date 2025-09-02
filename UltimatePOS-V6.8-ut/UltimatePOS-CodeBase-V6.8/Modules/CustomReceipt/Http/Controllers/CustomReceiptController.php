<?php

namespace Modules\CustomReceipt\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Transaction;
use App\BusinessLocation;
use App\InvoiceLayout;
use App\Utils\Util;
use Barryvdh\DomPDF\Facade\Pdf;

class CustomReceiptController extends Controller
{
    protected $commonUtil;

    public function __construct(Util $commonUtil)
    {
        $this->commonUtil = $commonUtil;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!auth()->user()->can('sell.view') && !auth()->user()->can('direct_sell.access')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = request()->session()->get('user.business_id');
        $transactions = Transaction::where('business_id', $business_id)
            ->where('type', 'sell')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('customreceipt::index', compact('transactions'));
    }

    /**
     * Generate custom thermal receipt for a transaction.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function generateReceipt($id)
    {
        if (!auth()->user()->can('sell.view') && !auth()->user()->can('direct_sell.access')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = request()->session()->get('user.business_id');
        
        $transaction = Transaction::where('business_id', $business_id)
            ->where('id', $id)
            ->with(['sell_lines', 'payment_lines', 'contact', 'business', 'location'])
            ->firstOrFail();

        $receipt_details = $this->prepareReceiptData($transaction);

        // Generate PDF using the thermal receipt template
        $pdf = PDF::loadView('sale_pos.receipts.thermal_restaurant', compact('receipt_details'));
        
        $pdf->setPaper('a4', 'portrait');
        
        return $pdf->stream('receipt-' . $transaction->invoice_no . '.pdf');
    }

    /**
     * Print thermal receipt.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function printReceipt($id)
    {
        if (!auth()->user()->can('sell.view') && !auth()->user()->can('direct_sell.access')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = request()->session()->get('user.business_id');
        
        $transaction = Transaction::where('business_id', $business_id)
            ->where('id', $id)
            ->with(['sell_lines', 'payment_lines', 'contact', 'business', 'location'])
            ->firstOrFail();

        $receipt_details = $this->prepareReceiptData($transaction);

        return view('sale_pos.receipts.thermal_restaurant', compact('receipt_details'));
    }

    /**
     * Prepare receipt data for the template.
     *
     * @param Transaction $transaction
     * @return object
     */
    private function prepareReceiptData($transaction)
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
        $receipt_details->invoice_date = $this->commonUtil->format_date($transaction->transaction_date, true);
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
