<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt-{{$receipt_details->invoice_no}}</title>
    <style>
        body {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            line-height: 1.2;
            margin: 0;
            padding: 10px;
            background: white;
            color: black;
        }
        .receipt {
            width: 100%;
            max-width: 300px;
            margin: 0 auto;
        }
        .text-center { text-align: center; }
        .text-left { text-align: left; }
        .text-right { text-align: right; }
        .bold { font-weight: bold; }
        .underline { text-decoration: underline; }
        .separator {
            border-top: 1px dashed #000;
            margin: 5px 0;
        }
        .item-row {
            display: flex;
            justify-content: space-between;
            margin: 2px 0;
        }
        .item-name {
            flex: 1;
            text-align: left;
        }
        .item-price {
            text-align: right;
            min-width: 80px;
        }
        .header-info {
            margin-bottom: 10px;
        }
        .business-name {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .business-address {
            font-size: 10px;
            margin-bottom: 3px;
        }
        .vat-info {
            font-size: 10px;
            margin-bottom: 5px;
        }
        .pos-info {
            font-size: 10px;
            margin-bottom: 5px;
        }
        .transaction-info {
            margin: 10px 0;
        }
        .dine-in {
            text-align: center;
            margin: 5px 0;
            font-weight: bold;
        }
        .items-section {
            margin: 10px 0;
        }
        .summary-section {
            margin: 10px 0;
        }
        .payment-section {
            margin: 10px 0;
        }
        .vat-breakdown {
            margin: 10px 0;
        }
        .customer-info {
            margin: 10px 0;
        }
        .feedback-section {
            margin: 15px 0;
            text-align: center;
        }
        .qr-code {
            text-align: center;
            margin: 10px 0;
        }
        .footer-info {
            margin: 10px 0;
            font-size: 10px;
        }
        .table-info {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class="receipt">
        <!-- Business Header -->
        <div class="header-info text-center">
            <div class="business-name">
                @if(!empty($receipt_details->display_name))
                    {{$receipt_details->display_name}}
                @endif
            </div>
            <div class="business-address">
                @if(!empty($receipt_details->address))
                    {!! $receipt_details->address !!}
                @endif
            </div>
            <div class="vat-info">
                @if(!empty($receipt_details->tax_info1))
                    VAT Registration TIN: {{$receipt_details->tax_info1}}
                @endif
            </div>
            <div class="pos-info">
                @if(!empty($receipt_details->invoice_no_prefix))
                    POS04-SN: {{$receipt_details->invoice_no_prefix}}
                @endif
                @if(!empty($receipt_details->invoice_no))
                    MIN#{{$receipt_details->invoice_no}}
                @endif
            </div>
        </div>

        <div class="separator"></div>

        <!-- Invoice Header -->
        <div class="text-center">
            <div class="bold">
                @if(!empty($receipt_details->invoice_heading))
                    {{$receipt_details->invoice_heading}}
                @else
                    SALES INVOICE
                @endif
            </div>
        </div>

        <!-- Transaction Details -->
        <div class="transaction-info">
            <div class="item-row">
                <span class="item-name">Date & Time:</span>
                <span class="item-price">{{$receipt_details->invoice_date}}</span>
            </div>
            <div class="item-row">
                <span class="item-name">Invoice No:</span>
                <span class="item-price">{{$receipt_details->invoice_no}}</span>
            </div>
            @if(!empty($receipt_details->service_staff))
            <div class="item-row">
                <span class="item-name">Cashier:</span>
                <span class="item-price">{{$receipt_details->service_staff}}</span>
            </div>
            @endif
        </div>

        <!-- Dine-in Indicator -->
        <div class="dine-in">
            -DINE-IN-
        </div>

        <div class="separator"></div>

        <!-- Items Section -->
        <div class="items-section">
            @foreach($receipt_details->lines as $line)
            <div class="item-row">
                <span class="item-name">{{$line->quantity}} {{$line->product_name}}</span>
                <span class="item-price">{{number_format($line->unit_price_inc_tax, 3)}}</span>
            </div>
            @endforeach
        </div>

        <div class="separator"></div>

        <!-- Summary -->
        <div class="summary-section">
            <div class="item-row">
                <span class="item-name">Total Items:</span>
                <span class="item-price">{{count($receipt_details->lines)}} Item(s)</span>
            </div>
            <div class="item-row bold">
                <span class="item-name">TOTAL DUE:</span>
                <span class="item-price">{{number_format($receipt_details->final_total, 2)}}</span>
            </div>
        </div>

        <!-- Payment Method -->
        <div class="payment-section">
            @if(!empty($receipt_details->payment_lines))
            @foreach($receipt_details->payment_lines as $payment)
            <div class="item-row">
                <span class="item-name">{{$payment->method}} - {{number_format($payment->amount, 2)}}</span>
            </div>
            @endforeach
            @endif
        </div>

        <div class="separator"></div>

        <!-- VAT Breakdown -->
        <div class="vat-breakdown">
            <div class="item-row">
                <span class="item-name">VATable Sales:</span>
                <span class="item-price">{{number_format($receipt_details->total_before_tax, 2)}}</span>
            </div>
            <div class="item-row">
                <span class="item-name">VAT-Exempt Sales:</span>
                <span class="item-price">0.00</span>
            </div>
            <div class="item-row">
                <span class="item-name">VAT Zero-Rated Sales:</span>
                <span class="item-price">0.00</span>
            </div>
            <div class="item-row">
                <span class="item-name">VAT Amount:</span>
                <span class="item-price">{{number_format($receipt_details->tax_amount, 2)}}</span>
            </div>
        </div>

        <div class="separator"></div>

        <!-- Customer Information -->
        <div class="customer-info">
            <div class="item-row">
                <span class="item-name">Cust Name:</span>
                <span class="item-price"></span>
            </div>
            <div class="item-row">
                <span class="item-name">Address:</span>
                <span class="item-price"></span>
            </div>
            <div class="item-row">
                <span class="item-name">TIN:</span>
                <span class="item-price"></span>
            </div>
            <div class="item-row">
                <span class="item-name">Bus Style:</span>
                <span class="item-price"></span>
            </div>
        </div>

        <!-- Table Information -->
        @if(!empty($receipt_details->table))
        <div class="table-info text-center">
            <div class="bold">Table #{{$receipt_details->table}}</div>
        </div>
        @endif

        <!-- Feedback Section -->
        <div class="feedback-section">
            <div class="text-center">
                <div class="bold">WE LOVE TO HEAR YOU</div>
                <div>Scan the QR Code below or Share your feedback at</div>
                <div>chowking-ph.welovetohearyou.com</div>
            </div>
            
            <div class="qr-code">
                <!-- QR Code placeholder - you can generate actual QR code here -->
                <div style="width: 80px; height: 80px; border: 1px solid #000; margin: 0 auto; display: flex; align-items: center; justify-content: center;">
                    QR
                </div>
            </div>
            
            <div class="text-center">
                <div>Have specific question or need assistance</div>
                <div>Visit chowking.ph/contact-us</div>
            </div>
        </div>

        <div class="separator"></div>

        <!-- Footer -->
        <div class="footer-info text-center">
            <div class="bold">THANK YOU, AND PLEASE COME AGAIN</div>
            <div>This serves as a SALES INVOICE</div>
            <div>ANSI Information Systems, Inc.</div>
            <div>Tytana St., Manila</div>
            <div>VAT Reg TIN: 000-330-515-000</div>
            <div>ACCREDITATION NO.03000033051500000712638</div>
            <div>Date Issued: 04/16/2007</div>
            <div>Valid Until: 07/31/2025</div>
        </div>
    </div>
</body>
</html>

