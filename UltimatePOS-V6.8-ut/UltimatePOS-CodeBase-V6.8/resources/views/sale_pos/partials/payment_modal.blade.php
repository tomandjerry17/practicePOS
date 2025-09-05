<div class="modal fade" tabindex="-1" role="dialog" id="modal_payment">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">@lang('lang_v1.payment')</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 mb-12">
                        <strong>@lang('lang_v1.advance_balance'):</strong> <span id="advance_balance_text"></span>
                        {!! Form::hidden('advance_balance', null, [
                            'id' => 'advance_balance',
                            'data-error-msg' => __('lang_v1.required_advance_balance_not_available'),
                        ]) !!}
                    </div>
                    <div class="col-md-9">
                        <div class="row">
                            <div id="payment_rows_div">
                                @php
                                    $pos_settings = !empty(session()->get('business.pos_settings')) ? json_decode(session()->get('business.pos_settings'), true) : [];
                                    $show_in_pos = '';


                                    if (isset($pos_settings['enable_cash_denomination_on']) && ($pos_settings['enable_cash_denomination_on'] == 'all_screens' || $pos_settings['enable_cash_denomination_on'] == 'pos_screen')) {
                                        $show_in_pos = true;
                                    }
                                    
                                @endphp
                                @foreach ($payment_lines as $payment_line)
                                    @if ($payment_line['is_return'] == 1)
                                        @php
                                            $change_return = $payment_line;
                                        @endphp

                                        @continue
                                    @endif

                                    @include('sale_pos.partials.payment_row', [
                                        'removable' => !$loop->first,
                                        'row_index' => $loop->index,
                                        'payment_line' => $payment_line,
                                        'show_denomination' => true,
                                        'show_in_pos' => $show_in_pos,
                                    ])
                                @endforeach
                            </div>
                            <input type="hidden" id="payment_row_index" value="{{ count($payment_lines) }}">
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <button type="button" class="tw-dw-btn tw-dw-btn-primary tw-text-white tw-dw-btn-sm tw-w-full"
                                    id="add-payment-row">@lang('sale.add_payment_row')</button>
                            </div>
                        </div>

                        <br>
                        <div class="row @if ($change_return['amount'] == 0) hide @endif payment_row"
                            id="change_return_payment_data">
                            <div class="col-md-12">
                                <div class="box box-solid payment_row bg-lightgray">
                                    <div class="box-body">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                {!! Form::label('change_return_method', __('lang_v1.change_return_payment_method') . ':*') !!}
                                                <div class="input-group">
                                                    <span class="input-group-addon">
                                                        <i class="fas fa-money-bill-alt"></i>
                                                    </span>
                                                    @php
                                                        $_payment_method = empty($change_return['method']) && array_key_exists('cash', $payment_types) ? 'cash' : $change_return['method'];

                                                        $_payment_types = $payment_types;
                                                        if (isset($_payment_types['advance'])) {
                                                            unset($_payment_types['advance']);
                                                        }
                                                    @endphp
                                                    {!! Form::select('payment[change_return][method]', $_payment_types, $_payment_method, [
                                                        'class' => 'form-control col-md-12 payment_types_dropdown',
                                                        'id' => 'change_return_method',
                                                        'style' => 'width:100%;',
                                                    ]) !!}
                                                </div>
                                            </div>
                                        </div>
                                        @if (!empty($accounts))
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    {!! Form::label('change_return_account', __('lang_v1.change_return_payment_account') . ':') !!}
                                                    <div class="input-group">
                                                        <span class="input-group-addon">
                                                            <i class="fas fa-money-bill-alt"></i>
                                                        </span>
                                                        {!! Form::select(
                                                            'payment[change_return][account_id]',
                                                            $accounts,
                                                            !empty($change_return['account_id']) ? $change_return['account_id'] : '',
                                                            ['class' => 'form-control select2', 'id' => 'change_return_account', 'style' => 'width:100%;'],
                                                        ) !!}
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="clearfix"></div>
                                        @include('sale_pos.partials.payment_type_details', [
                                            'payment_line' => $change_return,
                                            'row_index' => 'change_return',
                                        ])
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    {!! Form::label('sale_note', __('sale.sell_note') . ':') !!}
                                    {!! Form::textarea('sale_note', !empty($transaction) ? $transaction->additional_notes : null, [
                                        'class' => 'form-control',
                                        'rows' => 3,
                                        'placeholder' => __('sale.sell_note'),
                                    ]) !!}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    {!! Form::label('staff_note', __('sale.staff_note') . ':') !!}
                                    {!! Form::textarea('staff_note', !empty($transaction) ? $transaction->staff_note : null, [
                                        'class' => 'form-control',
                                        'rows' => 3,
                                        'placeholder' => __('sale.staff_note'),
                                    ]) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="box box-solid bg-orange">
                            <div class="box-body">
                                <div class="col-md-12">
                                    <strong>
                                        @lang('lang_v1.total_items'):
                                    </strong>
                                    <br />
                                    <span class="lead text-bold total_quantity">0</span>
                                </div>

                                <div class="col-md-12">
                                    <hr>
                                    <strong>
                                        @lang('sale.total_payable'):
                                    </strong>
                                    <br />
                                    <span class="lead text-bold total_payable_span">0</span>
                                </div>

                                <div class="col-md-12">
                                    <hr>
                                    <strong>
                                        @lang('lang_v1.total_paying'):
                                    </strong>
                                    <br />
                                    <span class="lead text-bold total_paying">0</span>
                                    <input type="hidden" id="total_paying_input">
                                </div>

                                <div class="col-md-12">
                                    <hr>
                                    <strong>
                                        @lang('lang_v1.change_return'):
                                    </strong>
                                    <br />
                                    <span class="lead text-bold change_return_span">0</span>
                                    {!! Form::hidden('change_return', $change_return['amount'], [
                                        'class' => 'form-control change_return input_number',
                                        'required',
                                        'id' => 'change_return',
                                    ]) !!}
                                    <!-- <span class="lead text-bold total_quantity">0</span> -->
                                    @if (!empty($change_return['id']))
                                        <input type="hidden" name="change_return_id"
                                            value="{{ $change_return['id'] }}">
                                    @endif
                                </div>

                                <!-- Balance field removed - not needed for POS as full payment is always collected -->



                            </div>
                            <!-- /.box-body -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <!-- BIR Receipt Template Selection -->
                <div class="row mb-3" style="width: 100%;">
                    <div class="col-md-12">
                        <div class="alert alert-info" style="margin-bottom: 10px;">
                            <strong><i class="fas fa-receipt"></i> BIR Receipt Template:</strong>
                            <span id="selected-bir-template">None selected</span>
                        </div>
                        <div class="btn-group" role="group" style="width: 100%;">
                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="selectBIRTemplate('A1')">
                                <i class="fas fa-file-invoice"></i> A1 (OR)
                            </button>
                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="selectBIRTemplate('A2')">
                                <i class="fas fa-file-invoice"></i> A2 (SI)
                            </button>
                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="selectBIRTemplate('A3')">
                                <i class="fas fa-file-invoice"></i> A3 (CI)
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="selectBIRTemplate(null)">
                                <i class="fas fa-times"></i> Skip BIR
                            </button>
                        </div>
                    </div>
                </div>
                
                <button type="button" class="tw-dw-btn tw-dw-btn-neutral tw-text-white" data-dismiss="modal">@lang('messages.close')</button>
                <button type="submit" class="tw-dw-btn tw-dw-btn-primary tw-text-white" id="pos-save">@lang('sale.finalize_payment')</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Used for express checkout card transaction -->
<div class="modal fade" tabindex="-1" role="dialog" id="card_details_modal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">@lang('lang_v1.card_transaction_details')</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">

                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::label('card_number', __('lang_v1.card_no')) !!}
                                {!! Form::text('', null, [
                                    'class' => 'form-control',
                                    'placeholder' => __('lang_v1.card_no'),
                                    'id' => 'card_number',
                                    'autofocus',
                                ]) !!}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::label('card_holder_name', __('lang_v1.card_holder_name')) !!}
                                {!! Form::text('', null, [
                                    'class' => 'form-control',
                                    'placeholder' => __('lang_v1.card_holder_name'),
                                    'id' => 'card_holder_name',
                                ]) !!}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::label('card_transaction_number', __('lang_v1.card_transaction_no')) !!}
                                {!! Form::text('', null, [
                                    'class' => 'form-control',
                                    'placeholder' => __('lang_v1.card_transaction_no'),
                                    'id' => 'card_transaction_number',
                                ]) !!}
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-md-3">
                            <div class="form-group">
                                {!! Form::label('card_type', __('lang_v1.card_type')) !!}
                                {!! Form::select('', ['visa' => 'Visa', 'master' => 'MasterCard'], 'visa', [
                                    'class' => 'form-control select2',
                                    'id' => 'card_type',
                                ]) !!}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                {!! Form::label('card_month', __('lang_v1.month')) !!}
                                {!! Form::text('', null, [
                                    'class' => 'form-control',
                                    'placeholder' => __('lang_v1.month'),
                                    'id' => 'card_month',
                                ]) !!}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                {!! Form::label('card_year', __('lang_v1.year')) !!}
                                {!! Form::text('', null, ['class' => 'form-control', 'placeholder' => __('lang_v1.year'), 'id' => 'card_year']) !!}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                {!! Form::label('card_security', __('lang_v1.security_code')) !!}
                                {!! Form::text('', null, [
                                    'class' => 'form-control',
                                    'placeholder' => __('lang_v1.security_code'),
                                    'id' => 'card_security',
                                ]) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="tw-dw-btn tw-dw-btn-primary tw-text-white" id="pos-save-card">@lang('sale.finalize_payment')</button>
            </div>
        </div>
    </div>
</div>

<script>
// BIR Receipt Template Selection
let selectedBIRTemplate = null;

function selectBIRTemplate(templateCode) {
    console.log('BIR Modal: selectBIRTemplate called with:', templateCode);
    selectedBIRTemplate = templateCode;
    
    // Update visual feedback
    $('.btn-group .btn').removeClass('btn-primary').addClass('btn-outline-primary');
    $('.btn-group .btn').removeClass('btn-secondary').addClass('btn-outline-secondary');
    
    if (templateCode) {
        // Highlight selected template button
        event.target.classList.remove('btn-outline-primary');
        event.target.classList.add('btn-primary');
        
        // Update display text
        let templateNames = {
            'A1': 'Official Receipt (OR)',
            'A2': 'Sales Invoice (SI)', 
            'A3': 'Cash Invoice (CI)'
        };
        $('#selected-bir-template').text(templateNames[templateCode]);
        
        // Store for later use
        window.birReceiptTemplate = templateCode;
        
        console.log('BIR Modal: Template selected and stored:', templateCode);
        console.log('BIR Modal: window.birReceiptTemplate =', window.birReceiptTemplate);
    } else {
        // Highlight skip button
        event.target.classList.remove('btn-outline-secondary');
        event.target.classList.add('btn-secondary');
        
        // Update display text
        $('#selected-bir-template').text('Skipped - Using regular receipt');
        
        // Clear selection
        window.birReceiptTemplate = null;
        
        console.log('BIR Modal: BIR Receipt skipped, window.birReceiptTemplate =', window.birReceiptTemplate);
    }
}

// Initialize on modal show
$('#modal_payment').on('show.bs.modal', function() {
    // Reset selection when modal opens
    selectedBIRTemplate = null;
    window.birReceiptTemplate = null;
    $('#selected-bir-template').text('None selected');
    $('.btn-group .btn').removeClass('btn-primary').addClass('btn-outline-primary');
    $('.btn-group .btn').removeClass('btn-secondary').addClass('btn-outline-secondary');
});

// Function to generate BIR receipt
window.generateBIRReceipt = function() {
    console.log('BIR Modal: generateBIRReceipt function called');
    console.log('BIR Modal: Template:', window.birReceiptTemplate);
    
    // Get transaction data
    var transactionData = getTransactionData();
    console.log('BIR Modal: Transaction data:', transactionData);
    
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
    
    console.log('BIR Modal: BIR Receipt Data prepared:', birReceiptData);
    
    // Generate BIR receipt
    $.ajax({
        url: '/bir-receipt/generate',
        method: 'POST',
        data: birReceiptData,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            console.log('BIR Receipt generated successfully');
            
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
            console.error('Error generating BIR receipt:', xhr);
            toastr.error('Error generating BIR receipt. Please try again.');
            
            // Fallback to regular receipt
            $('#modal_payment').modal('hide');
            pos_form_obj.submit();
        }
    });
};

// Function to get transaction data
function getTransactionData() {
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
</script>
