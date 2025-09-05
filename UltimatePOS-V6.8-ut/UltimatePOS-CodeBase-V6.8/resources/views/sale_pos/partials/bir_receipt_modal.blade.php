<!-- BIR Receipt Template Selection Modal -->
<div class="modal fade" id="bir_receipt_modal" tabindex="-1" role="dialog" aria-labelledby="birReceiptModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="birReceiptModalLabel">
                    <i class="fas fa-receipt"></i> Select BIR Receipt Template
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    Please select a BIR-accredited receipt template for this transaction.
                </div>
                
                <div class="row" id="bir-template-selection">
                    <!-- Templates will be loaded here -->
                    <div class="col-12 text-center">
                        <div class="spinner-border" role="status">
                            <span class="sr-only">Loading templates...</span>
                        </div>
                    </div>
                </div>
                
                <div class="mt-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="skip_bir_receipt" value="1">
                        <label class="form-check-label" for="skip_bir_receipt">
                            Skip BIR Receipt (Use regular receipt)
                        </label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="proceed-with-bir-receipt" disabled>
                    <i class="fas fa-check"></i> Proceed with Payment
                </button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    let selectedTemplate = null;
    let originalPaymentMethod = null;
    
    // Load BIR templates when modal is shown
    $('#bir_receipt_modal').on('show.bs.modal', function() {
        loadBIRTemplates();
    });
    
    // Handle template selection
    $(document).on('click', '.bir-template-card', function() {
        $('.bir-template-card').removeClass('border-primary bg-light');
        $(this).addClass('border-primary bg-light');
        selectedTemplate = $(this).data('template-code');
        $('#proceed-with-bir-receipt').prop('disabled', false);
    });
    
    // Handle skip BIR receipt checkbox
    $('#skip_bir_receipt').change(function() {
        if ($(this).is(':checked')) {
            $('.bir-template-card').removeClass('border-primary bg-light');
            selectedTemplate = null;
            $('#proceed-with-bir-receipt').prop('disabled', false);
        } else {
            $('#proceed-with-bir-receipt').prop('disabled', selectedTemplate === null);
        }
    });
    
    // Proceed with payment
    $('#proceed-with-bir-receipt').click(function() {
        $('#bir_receipt_modal').modal('hide');
        
        // Store the selected template for later use
        if (selectedTemplate) {
            window.birReceiptTemplate = selectedTemplate;
            toastr.info('BIR Receipt template selected: ' + selectedTemplate);
        } else {
            window.birReceiptTemplate = null;
            toastr.info('BIR Receipt skipped - using regular receipt');
        }
        
        // Now proceed with the original payment method
        if (originalPaymentMethod) {
            if (originalPaymentMethod === 'multi_pay') {
                // Trigger the original pos-finalize behavior
                setTimeout(function() {
                    $('button#pos-finalize').trigger('click');
                }, 100);
            } else {
                // Trigger the original express finalize behavior
                setTimeout(function() {
                    $('button.pos-express-finalize[data-pay_method="' + originalPaymentMethod + '"]').trigger('click');
                }, 100);
            }
        }
    });
    
    function loadBIRTemplates() {
        $.ajax({
            url: '/bir-receipt/templates',
            method: 'GET',
            success: function(response) {
                let html = '';
                if (response.templates && response.templates.length > 0) {
                    response.templates.forEach(function(template) {
                        html += `
                            <div class="col-md-6 mb-3">
                                <div class="card bir-template-card" data-template-code="${template.template_code}" style="cursor: pointer;">
                                    <div class="card-body">
                                        <h6 class="card-title">${template.template_name}</h6>
                                        <p class="card-text small text-muted">${template.description}</p>
                                        <div class="text-right">
                                            <span class="badge badge-info">${template.template_code}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                } else {
                    html = '<div class="col-12 text-center"><p class="text-muted">No BIR templates available</p></div>';
                }
                $('#bir-template-selection').html(html);
            },
            error: function() {
                $('#bir-template-selection').html('<div class="col-12 text-center"><p class="text-danger">Error loading templates</p></div>');
            }
        });
    }
    
    // Store the function globally for use by payment buttons
    window.showBIRReceiptModal = function(paymentMethod) {
        console.log('BIR Receipt Modal: showBIRReceiptModal called with payment method:', paymentMethod);
        originalPaymentMethod = paymentMethod;
        selectedTemplate = null;
        $('#skip_bir_receipt').prop('checked', false);
        $('#proceed-with-bir-receipt').prop('disabled', true);
        console.log('BIR Receipt Modal: Showing modal...');
        $('#bir_receipt_modal').modal('show');
    };
    
    console.log('BIR Receipt Modal: Script loaded and showBIRReceiptModal function defined');
});
</script>
