<!-- Modern Retail Store Receipt Template - Optimized for 80mm Thermal Paper -->
<div class="row" style="color: #000000 !important; font-family: 'Courier New', monospace; font-size: 12px; max-width: 80mm; margin: 0 auto;">
    
    <!-- Business Header -->
    <div class="col-xs-12 text-center" style="margin-bottom: 15px;">
        @if(!empty($receipt_details->logo))
            <img style="max-height: 60px; width: auto; margin-bottom: 8px;" src="{{$receipt_details->logo}}" class="img img-responsive center-block">
        @endif
        
        <!-- Business Name -->
        <h2 style="margin: 5px 0; font-size: 16px; font-weight: bold; text-transform: uppercase;">
            @if(!empty($receipt_details->display_name))
                {{$receipt_details->display_name}}
            @endif
        </h2>
        
        <!-- Business Subtitle -->
        @if(!empty($receipt_details->sub_heading_line1))
            <p style="margin: 2px 0; font-size: 11px;">{{ $receipt_details->sub_heading_line1 }}</p>
        @endif
        
        <!-- Address -->
        @if(!empty($receipt_details->address))
            <p style="margin: 2px 0; font-size: 10px;">{!! $receipt_details->address !!}</p>
        @endif
        
        <!-- Contact Information -->
        @if(!empty($receipt_details->mobile))
            <p style="margin: 2px 0; font-size: 10px;">Tel: {{ $receipt_details->mobile }}</p>
        @endif
        @if(!empty($receipt_details->alternate_number))
            <p style="margin: 2px 0; font-size: 10px;">Alt: {{ $receipt_details->alternate_number }}</p>
        @endif
        @if(!empty($receipt_details->email))
            <p style="margin: 2px 0; font-size: 10px;">{{ $receipt_details->email }}</p>
        @endif
        
        <!-- Tax Information -->
        @if(!empty($receipt_details->tax_info1))
            <p style="margin: 2px 0; font-size: 9px;">VAT Reg TIN: {{ $receipt_details->tax_info1 }}</p>
        @endif
        @if(!empty($receipt_details->tax_info2))
            <p style="margin: 2px 0; font-size: 9px;">{{ $receipt_details->tax_info2 }}</p>
        @endif
    </div>
    
    <!-- Receipt Title and Transaction Details -->
    <div class="col-xs-12 text-center" style="margin-bottom: 15px; border-top: 1px dashed #000; border-bottom: 1px dashed #000; padding: 8px 0;">
        @if(!empty($receipt_details->invoice_heading))
            <h3 style="margin: 5px 0; font-size: 14px; font-weight: bold; text-transform: uppercase;">
                {!! $receipt_details->invoice_heading !!}
            </h3>
        @endif
        
        <!-- Invoice Number and Date -->
        <div style="margin: 5px 0; font-size: 10px;">
            @if(!empty($receipt_details->invoice_no_prefix))
                <strong>{{ $receipt_details->invoice_no_prefix }}{{$receipt_details->invoice_no}}</strong>
            @endif
        </div>
        
        <div style="margin: 3px 0; font-size: 10px;">
            @if(!empty($receipt_details->invoice_date))
                <strong>Date:</strong> {{$receipt_details->invoice_date}}
            @endif
        </div>
        
        <!-- Staff/Cashier Information -->
        @if(!empty($receipt_details->service_staff))
            <div style="margin: 3px 0; font-size: 10px;">
                <strong>Cashier:</strong> {{$receipt_details->service_staff}}
            </div>
        @endif
        
        <!-- Service Type -->
        @if(!empty($receipt_details->types_of_service))
            <div style="margin: 3px 0; font-size: 10px;">
                <strong>Service:</strong> {{$receipt_details->types_of_service}}
            </div>
        @endif
        
        <!-- Customer Information -->
        @if(!empty($receipt_details->customer))
            <div style="margin: 3px 0; font-size: 10px;">
                <strong>Customer:</strong> {{$receipt_details->customer}}
            </div>
        @endif
    </div>
    
    <!-- Items List -->
    <div class="col-xs-12" style="margin-bottom: 15px;">
        <table style="width: 100%; border-collapse: collapse; font-size: 10px;">
            <thead>
                <tr style="border-bottom: 1px solid #000;">
                    <th style="text-align: left; padding: 3px 0; font-weight: bold;">ITEM</th>
                    <th style="text-align: center; padding: 3px 0; font-weight: bold;">QTY</th>
                    <th style="text-align: right; padding: 3px 0; font-weight: bold;">PRICE</th>
                    <th style="text-align: right; padding: 3px 0; font-weight: bold;">TOTAL</th>
                </tr>
            </thead>
            <tbody>
                @forelse($receipt_details->lines as $line)
                    <tr style="border-bottom: 1px dotted #ccc;">
                        <td style="padding: 3px 0; text-align: left; width: 40%;">
                            <strong>{{$line['product']}}</strong>
                            @if(!empty($line['variation']))
                                <br><small>{{$line['variation']}}</small>
                            @endif
                            @if(!empty($line['sub_sku']))
                                <br><small>({{$line['sub_sku']}})</small>
                            @endif
                        </td>
                        <td style="padding: 3px 0; text-align: center; width: 15%;">
                            {{(int)$line['quantity']}}
                        </td>
                        <td style="padding: 3px 0; text-align: right; width: 20%;">
                            @if(!empty($line['unit_price']))
                                {{$line['unit_price']}}
                            @else
                                0.00
                            @endif
                        </td>
                        <td style="padding: 3px 0; text-align: right; width: 25%; font-weight: bold;">
                            @if(!empty($line['line_total']))
                                {{$line['line_total']}}
                            @else
                                0.00
                            @endif
                        </td>
                    </tr>
                    @if(!empty($line['modifiers']))
                        @foreach($line['modifiers'] as $modifier)
                            <tr style="border-bottom: 1px dotted #ccc;">
                                <td style="padding: 2px 0; text-align: left; padding-left: 15px; font-size: 9px;">
                                    + {{$modifier['name']}}
                                </td>
                                <td style="padding: 2px 0; text-align: center; font-size: 9px;">
                                    {{(int)($modifier['quantity'] ?? 1)}}
                                </td>
                                <td style="padding: 2px 0; text-align: right; font-size: 9px;">
                                    @if(!empty($modifier['unit_price']))
                                        {{$modifier['unit_price']}}
                                    @else
                                        0.00
                                    @endif
                                </td>
                                <td style="padding: 2px 0; text-align: right; font-size: 9px;">
                                    @if(!empty($modifier['line_total']))
                                        {{$modifier['line_total']}}
                                    @else
                                        0.00
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @endif
                @empty
                    <tr>
                        <td colspan="4" style="text-align: center; padding: 10px 0;">No items found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Summary Section -->
    <div class="col-xs-12" style="margin-bottom: 15px; border-top: 1px dashed #000; padding-top: 8px;">
        <table style="width: 100%; border-collapse: collapse; font-size: 11px;">
            <tr>
                <td style="text-align: left; padding: 2px 0;"><strong>Subtotal:</strong></td>
                <td style="text-align: right; padding: 2px 0;">
                    @if(!empty($receipt_details->subtotal))
                        {{$receipt_details->subtotal}}
                    @else
                        0.00
                    @endif
                </td>
            </tr>
            
            @if(!empty($receipt_details->total_discount) && $receipt_details->total_discount > 0)
                <tr>
                    <td style="text-align: left; padding: 2px 0;"><strong>Discount:</strong></td>
                    <td style="text-align: right; padding: 2px 0; color: #d00;">
                        -{{$receipt_details->total_discount}}
                    </td>
                </tr>
            @endif
            
            @if(!empty($receipt_details->order_tax) && $receipt_details->order_tax > 0)
                <tr>
                    <td style="text-align: left; padding: 2px 0;"><strong>Tax:</strong></td>
                    <td style="text-align: right; padding: 2px 0;">
                        {{$receipt_details->order_tax}}
                    </td>
                </tr>
            @endif
            
            @if(!empty($receipt_details->shipping_charges) && $receipt_details->shipping_charges > 0)
                <tr>
                    <td style="text-align: left; padding: 2px 0;"><strong>Shipping:</strong></td>
                    <td style="text-align: right; padding: 2px 0;">
                        {{$receipt_details->shipping_charges}}
                    </td>
                </tr>
            @endif
            
            <tr style="border-top: 1px solid #000; border-bottom: 1px solid #000;">
                <td style="text-align: left; padding: 5px 0; font-size: 12px; font-weight: bold;">TOTAL DUE:</td>
                <td style="text-align: right; padding: 5px 0; font-size: 12px; font-weight: bold;">
                    @if(!empty($receipt_details->final_total))
                        {{$receipt_details->final_total}}
                    @else
                        0.00
                    @endif
                </td>
            </tr>
        </table>
    </div>
    
    <!-- Payment Section -->
    <div class="col-xs-12" style="margin-bottom: 15px; border: 2px solid #000; padding: 8px; background-color: #f9f9f9;">
        <h4 style="margin: 0 0 8px 0; font-size: 12px; text-align: center; text-transform: uppercase; font-weight: bold;">PAYMENT DETAILS</h4>
        
        @if(!empty($receipt_details->payments))
            @foreach($receipt_details->payments as $payment)
                <div style="margin: 3px 0; font-size: 10px;">
                    <span style="font-weight: bold;">{{ucfirst($payment['method'])}}:</span>
                    <span style="float: right;">{{$payment['amount']}}</span>
                </div>
                
                <!-- Amount Received and Change for Cash Payments -->
                @if($payment['method'] == 'cash' && !empty($payment['cash_amount_received']))
                    <div style="margin: 3px 0; font-size: 10px;">
                        <span style="font-weight: bold;">Amount Received:</span>
                        <span style="float: right;">{{$payment['cash_amount_received']}}</span>
                    </div>
                    @if(!empty($payment['cash_change']))
                        <div style="margin: 3px 0; font-size: 10px; background-color: #fff; padding: 3px; border: 1px solid #000;">
                            <span style="font-weight: bold; color: #d00;">CHANGE:</span>
                            <span style="float: right; font-weight: bold; color: #d00;">{{$payment['cash_change']}}</span>
                        </div>
                    @endif
                @endif
            @endforeach
        @endif
    </div>
    
    <!-- Business Information Footer -->
    <div class="col-xs-12 text-center" style="margin-bottom: 15px; border-top: 1px dashed #000; padding-top: 8px;">
        <p style="margin: 3px 0; font-size: 10px; font-weight: bold;">Business Hours</p>
        <p style="margin: 2px 0; font-size: 9px;">Monday - Saturday: 9:00 AM - 9:00 PM</p>
        <p style="margin: 2px 0; font-size: 9px;">Sunday: 10:00 AM - 6:00 PM</p>
        
        <p style="margin: 8px 0; font-size: 10px; font-weight: bold;">Return Policy</p>
        <p style="margin: 2px 0; font-size: 9px;">Returns accepted within 30 days</p>
        <p style="margin: 2px 0; font-size: 9px;">Original receipt required</p>
        
        <p style="margin: 8px 0; font-size: 10px; font-weight: bold;">Customer Service</p>
        <p style="margin: 2px 0; font-size: 9px;">For assistance, call: {{ $receipt_details->mobile ?? 'N/A' }}</p>
        <p style="margin: 2px 0; font-size: 9px;">Email: {{ $receipt_details->email ?? 'N/A' }}</p>
        
        @if(!empty($receipt_details->website))
            <p style="margin: 3px 0; font-size: 9px;">Visit us online: {{ $receipt_details->website }}</p>
        @endif
        
        @if(!empty($receipt_details->social_media))
            <p style="margin: 3px 0; font-size: 9px;">Follow us: {{ $receipt_details->social_media }}</p>
        @endif
    </div>
    
    <!-- Thank You Message -->
    <div class="col-xs-12 text-center" style="margin-bottom: 15px;">
        <p style="margin: 5px 0; font-size: 12px; font-weight: bold;">Thank you for your purchase!</p>
        <p style="margin: 3px 0; font-size: 10px;">Please visit us again</p>
        <p style="margin: 3px 0; font-size: 9px;">Have a great day!</p>
    </div>
    
    <!-- QR Code Section -->
    @if(!empty($receipt_details->qr_code))
        <div class="col-xs-12 text-center" style="margin-bottom: 15px;">
            <p style="margin: 3px 0; font-size: 9px;">Scan for digital receipt</p>
            <img style="max-width: 60px; height: auto;" src="{{$receipt_details->qr_code}}" class="img img-responsive center-block">
        </div>
    @endif
    
    <!-- Receipt Footer -->
    <div class="col-xs-12 text-center" style="border-top: 1px dashed #000; padding-top: 8px;">
        <p style="margin: 2px 0; font-size: 8px;">This receipt serves as your official invoice</p>
        <p style="margin: 2px 0; font-size: 8px;">Keep this receipt for your records</p>
        <p style="margin: 2px 0; font-size: 8px;">Generated on: {{ date('Y-m-d H:i:s') }}</p>
    </div>
</div>
