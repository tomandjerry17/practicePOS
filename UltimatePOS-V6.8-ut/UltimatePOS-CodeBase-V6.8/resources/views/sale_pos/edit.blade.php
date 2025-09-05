@extends('layouts.app')

@section('title', __('sale.pos_sale'))

@section('content')
<section class="content no-print">
	<input type="hidden" id="amount_rounding_method" value="{{$pos_settings['amount_rounding_method'] ?? ''}}">
	@if(!empty($pos_settings['allow_overselling']))
		<input type="hidden" id="is_overselling_allowed">
	@endif
	@if(session('business.enable_rp') == 1)
        <input type="hidden" id="reward_point_enabled">
    @endif
    @php
		$is_discount_enabled = $pos_settings['disable_discount'] != 1 ? true : false;
		$is_rp_enabled = session('business.enable_rp') == 1 ? true : false;
	@endphp
	{!! Form::open(['url' => action([\App\Http\Controllers\SellPosController::class, 'update'], [$transaction->id]), 'method' => 'post', 'id' => 'edit_pos_sell_form' ]) !!}
	{{ method_field('PUT') }}
	<div class="row mb-12">
		<div class="col-md-12 tw-pt-0 tw-mb-14">
			<div class="row tw-flex lg:tw-flex-row md:tw-flex-col sm:tw-flex-col tw-flex-col tw-items-start md:tw-gap-4">
				<div class="tw-px-3 tw-w-full  lg:tw-px-0 lg:tw-pr-0 @if(empty($pos_settings['hide_product_suggestion'])) lg:tw-w-[60%]  @else lg:tw-w-[100%] @endif">
					<div class="tw-shadow-[rgba(17,_17,_26,_0.1)_0px_0px_16px] tw-rounded-2xl tw-bg-white tw-mb-2 md:tw-mb-8 tw-p-2">
						<div class="box-body pb-0">
							{!! Form::hidden('location_id', $transaction->location_id, ['id' => 'location_id', 'data-receipt_printer_type' => !empty($location_printer_type) ? $location_printer_type : 'browser', 'data-default_payment_accounts' => $transaction->location->default_payment_accounts]); !!}
							<!-- sub_type -->
							{!! Form::hidden('sub_type', isset($sub_type) ? $sub_type : null) !!}
							<input type="hidden" id="item_addition_method" value="{{$business_details->item_addition_method}}">
								@include('sale_pos.partials.pos_form_edit')

								@include('sale_pos.partials.pos_form_totals', ['edit' => true])

								@include('sale_pos.partials.payment_modal')

								@if(empty($pos_settings['disable_suspend']))
									@include('sale_pos.partials.suspend_note_modal')
								@endif

								@if(empty($pos_settings['disable_recurring_invoice']))
									@include('sale_pos.partials.recurring_invoice_modal')
								@endif
							</div>
							@if(!empty($only_payment))
								<div class="overlay"></div>
							@endif
						</div>
					</div>
				@if(empty($pos_settings['hide_product_suggestion'])  && !isMobile() && empty($only_payment))
					<div class="col-md-5 no-padding">
						@include('sale_pos.partials.pos_sidebar')
					</div>
				@endif
			</div>
		</div>
	</div>
	@include('sale_pos.partials.pos_form_actions', ['edit' => true])
	{!! Form::close() !!}
</section>

<!-- This will be printed -->
<section class="invoice print_section" id="receipt_section">
</section>
<div class="modal fade contact_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
	@include('contact.create', ['quick_add' => true])
</div>
@if(empty($pos_settings['hide_product_suggestion']) && isMobile())
	@include('sale_pos.partials.mobile_product_suggestions')
@endif
<!-- /.content -->
<div class="modal fade register_details_modal" tabindex="-1" role="dialog" 
	aria-labelledby="gridSystemModalLabel">
</div>
<div class="modal fade close_register_modal" tabindex="-1" role="dialog" 
	aria-labelledby="gridSystemModalLabel">
</div>
<!-- quick product modal -->
<div class="modal fade quick_add_product_modal" tabindex="-1" role="dialog" aria-labelledby="modalTitle"></div>

@include('sale_pos.partials.configure_search_modal')

@include('sale_pos.partials.recent_transactions_modal')

@include('sale_pos.partials.weighing_scale_modal')
@include('sale_pos.partials.bir_receipt_modal')

@stop

@section('javascript')
	<script src="{{ asset('js/pos.js?v=' . $asset_v) }}"></script>
	<script src="{{ asset('js/printer.js?v=' . $asset_v) }}"></script>
	<script src="{{ asset('js/product.js?v=' . $asset_v) }}"></script>
	<script src="{{ asset('js/opening_stock.js?v=' . $asset_v) }}"></script>
	@include('sale_pos.partials.keyboard_shortcuts')

	<!-- Call restaurant module if defined -->
    @if(in_array('tables' ,$enabled_modules) || in_array('modifiers' ,$enabled_modules) || in_array('service_staff' ,$enabled_modules))
    	<script src="{{ asset('js/restaurant.js?v=' . $asset_v) }}"></script>
    @endif

    <!-- include module js -->
    @if(!empty($pos_module_data))
	    @foreach($pos_module_data as $key => $value)
            @if(!empty($value['module_js_path']))
                @includeIf($value['module_js_path'], ['view_data' => $value['view_data']])
            @endif
	    @endforeach
	@endif

    <!-- BIR Receipt Plugin Integration -->
    <script src="{{ asset('Modules/BIRReceiptPlugin/Resources/js/integration.js') }}"></script>
    
    <!-- BIR Receipt Generation Function -->
    <script>
    // Define the BIR receipt generation function globally
    window.generateBIRReceipt = function() {
        console.log('BIR: generateBIRReceipt function called');
        console.log('BIR: Template:', window.birReceiptTemplate);
        
        // Get transaction data
        var transactionData = getBIRTransactionData();
        console.log('BIR: Transaction data:', transactionData);
        
        // Prepare BIR receipt data
        var birReceiptData = {
            transaction_id: 'POS_' + Date.now(),
            template_code: window.birReceiptTemplate,
            format: 'html',
            customer_name: transactionData.customer_name,
            customer_phone: transactionData.customer_phone || '',
            customer_address: transactionData.customer_address || '',
            customer_tin: transactionData.customer_tin || '',
            items: transactionData.items,
            subtotal: transactionData.subtotal,
            tax_amount: transactionData.tax_amount,
            total_amount: transactionData.total_amount
        };
        
        console.log('BIR: BIR Receipt Data prepared:', birReceiptData);
        
        // Generate BIR receipt
        $.ajax({
            url: '/bir-receipt/generate',
            method: 'POST',
            data: birReceiptData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                console.log('BIR: Receipt generated successfully');
                
                // Close the payment modal
                $('#modal_payment').modal('hide');
                
                // Open BIR receipt in new window for printing
                var printWindow = window.open('', '_blank');
                printWindow.document.write(response);
                printWindow.document.close();
                printWindow.focus();
                
                // Auto-print the receipt
                setTimeout(function() {
                    printWindow.print();
                }, 500);
                
                // Show success message
                toastr.success('BIR Receipt generated successfully!');
                
                // Clear the form and reset
                setTimeout(function() {
                    location.reload();
                }, 2000);
            },
            error: function(xhr) {
                console.error('BIR: Error generating receipt:', xhr);
                toastr.error('Error generating BIR receipt. Using regular receipt.');
                
                // Fallback to regular receipt
                $('#modal_payment').modal('hide');
                if (typeof pos_form_obj !== 'undefined') {
                    pos_form_obj.submit();
                }
            }
        });
    };
    
    // Function to get transaction data
    function getBIRTransactionData() {
        var items = [];
        $('table#pos_table tbody .product_row').each(function() {
            var row = $(this);
            items.push({
                name: row.find('.product_name').text().trim(),
                quantity: parseFloat(row.find('.quantity').val()) || 1,
                unit_price: parseFloat(row.find('.unit_price').val()) || 0,
                total: parseFloat(row.find('.line_total').val()) || 0
            });
        });
        
        var subtotal = parseFloat($('#subtotal').text().replace(/[^\d.-]/g, '')) || 0;
        var taxAmount = parseFloat($('#order_tax').text().replace(/[^\d.-]/g, '')) || 0;
        var totalAmount = parseFloat($('#total_payable').text().replace(/[^\d.-]/g, '')) || 0;
        
        return {
            items: items,
            subtotal: subtotal,
            tax_amount: taxAmount,
            total_amount: totalAmount,
            customer_name: $('#customer_id option:selected').text() || 'Walk-in Customer',
            customer_phone: '',
            customer_address: '',
            customer_tin: ''
        };
    }
    
    console.log('BIR: generateBIRReceipt function defined globally');
    </script>
    
	
@endsection

@section('css')
	<style type="text/css">
		/*CSS to print receipts*/
		.print_section{
		    display: none;
		}
		@media print{
		    .print_section{
		        display: block !important;
		    }
		}
		@page {
		    size: 3.1in auto;/* width height */
		    height: auto !important;
		    margin-top: 0mm;
		    margin-bottom: 0mm;
		}
		.overlay {
			background: rgba(255,255,255,0) !important;
			cursor: not-allowed;
		}
	</style>
	<!-- include module css -->
    @if(!empty($pos_module_data))
        @foreach($pos_module_data as $key => $value)
            @if(!empty($value['module_css_path']))
                @includeIf($value['module_css_path'])
            @endif
        @endforeach
    @endif
@endsection