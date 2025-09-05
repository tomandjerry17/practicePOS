<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BIR Sales Invoice - {{ $receiptData['receipt_number'] }}</title>
    <style>
        @page {
            size: 80mm auto;
            margin: 0;
        }
        
        body {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            line-height: 1.2;
            margin: 0;
            padding: 10px;
            background: white;
            color: black;
            width: 80mm;
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
        
        .header {
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
        
        .tin-info {
            font-size: 10px;
            margin-bottom: 5px;
        }
        
        .receipt-title {
            font-size: 13px;
            font-weight: bold;
            text-align: center;
            margin: 10px 0;
            text-transform: uppercase;
        }
        
        .receipt-info {
            margin: 10px 0;
        }
        
        .receipt-info table {
            width: 100%;
            font-size: 11px;
        }
        
        .receipt-info td {
            padding: 2px 0;
        }
        
        .customer-info {
            margin: 10px 0;
            font-size: 11px;
        }
        
        .items-section {
            margin: 10px 0;
        }
        
        .item-row {
            display: flex;
            justify-content: space-between;
            margin: 2px 0;
            font-size: 11px;
        }
        
        .item-name {
            flex: 1;
            text-align: left;
        }
        
        .item-price {
            text-align: right;
            min-width: 80px;
        }
        
        .summary-section {
            margin: 10px 0;
        }
        
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin: 2px 0;
            font-size: 11px;
        }
        
        .total-row {
            font-weight: bold;
            border-top: 1px solid #000;
            padding-top: 5px;
            margin-top: 5px;
        }
        
        .footer {
            margin-top: 15px;
            font-size: 10px;
            text-align: center;
        }
        
        .bir-notice {
            font-size: 9px;
            margin-top: 10px;
            text-align: center;
        }
        
        @media print {
            body { margin: 0; padding: 5px; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="receipt">
        <!-- Business Header -->
        <div class="header text-center">
            @if($receiptData['business']->logo_path)
                <img src="{{ $receiptData['business']->logo_path }}" alt="Logo" style="max-height: 50px; margin-bottom: 5px;">
            @endif
            
            <div class="business-name">{{ $receiptData['business']->business_name }}</div>
            <div class="business-address">{{ $receiptData['business']->business_address }}</div>
            
            @if($receiptData['business']->business_phone)
                <div class="business-address">Tel: {{ $receiptData['business']->business_phone }}</div>
            @endif
            
            @if($receiptData['business']->business_email)
                <div class="business-address">{{ $receiptData['business']->business_email }}</div>
            @endif
            
            <div class="tin-info">TIN: {{ $receiptData['business']->tin_number }}</div>
        </div>
        
        <!-- Receipt Title -->
        <div class="receipt-title">Sales Invoice</div>
        
        <!-- Receipt Information -->
        <div class="receipt-info">
            <table>
                <tr>
                    <td class="text-left">SI No.:</td>
                    <td class="text-right">{{ $receiptData['receipt_number'] }}</td>
                </tr>
                <tr>
                    <td class="text-left">Date:</td>
                    <td class="text-right">{{ date('M d, Y', strtotime($receiptData['date'])) }}</td>
                </tr>
                <tr>
                    <td class="text-left">Time:</td>
                    <td class="text-right">{{ date('H:i:s', strtotime($receiptData['date'])) }}</td>
                </tr>
            </table>
        </div>
        
        <!-- Customer Information -->
        <div class="customer-info">
            <div class="bold">Bill To:</div>
            <div>{{ $receiptData['transaction']['customer_name'] }}</div>
            @if($receiptData['transaction']['customer_address'])
                <div>{{ $receiptData['transaction']['customer_address'] }}</div>
            @endif
            @if($receiptData['transaction']['customer_tin'])
                <div>TIN: {{ $receiptData['transaction']['customer_tin'] }}</div>
            @endif
        </div>
        
        <div class="separator"></div>
        
        <!-- Items Section -->
        <div class="items-section">
            <div class="item-row bold">
                <span class="item-name">Description</span>
                <span class="item-price">Amount</span>
            </div>
            <div class="separator"></div>
            
            @foreach($receiptData['transaction']['items'] as $item)
            <div class="item-row">
                <span class="item-name">{{ $item['name'] }} x{{ $item['quantity'] }}</span>
                <span class="item-price">₱{{ number_format($item['total'], 2) }}</span>
            </div>
            @endforeach
        </div>
        
        <div class="separator"></div>
        
        <!-- Summary Section -->
        <div class="summary-section">
            <div class="summary-row">
                <span>Subtotal:</span>
                <span>₱{{ number_format($receiptData['transaction']['subtotal'], 2) }}</span>
            </div>
            <div class="summary-row">
                <span>VAT (12%):</span>
                <span>₱{{ number_format($receiptData['transaction']['vat_amount'], 2) }}</span>
            </div>
            <div class="summary-row total-row">
                <span>TOTAL:</span>
                <span>₱{{ number_format($receiptData['transaction']['total_amount'], 2) }}</span>
            </div>
        </div>
        
        <div class="separator"></div>
        
        <!-- Footer -->
        <div class="footer">
            <div>Payment Terms: Due upon receipt</div>
            <div class="bir-notice">
                This invoice is issued in compliance with BIR RMC No. 77-2024
            </div>
        </div>
    </div>
</body>
</html>
