/**
 * BIR Receipt Payment Interceptor
 * This script intercepts payment button clicks to show BIR template selection
 */

console.log('BIR Payment Interceptor script loaded');

// Wait for jQuery to be available
function waitForJQuery() {
    if (typeof jQuery !== 'undefined') {
        console.log('jQuery is available, initializing BIR Payment Interceptor');
        initializeBIRInterceptor();
    } else {
        console.log('jQuery not available yet, waiting...');
        setTimeout(waitForJQuery, 100);
    }
}

function initializeBIRInterceptor() {
    $(document).ready(function() {
    console.log('BIR Payment Interceptor: Document ready');
    
    // Function to initialize interceptors
    function initializeInterceptors() {
        console.log('BIR Payment Interceptor: Initializing...');
        
        // Check if buttons exist
        var cashButton = $('button.pos-express-finalize[data-pay_method="cash"]');
        var multiPayButton = $('button#pos-finalize');
        
        console.log('Cash button found:', cashButton.length);
        console.log('Multi-pay button found:', multiPayButton.length);
        
        // Intercept Cash button clicks
        cashButton.off('click.bir').on('click.bir', function(e) {
            console.log('BIR Payment Interceptor: Cash button clicked');
            
            // Check if products are present
            if ($('table#pos_table tbody').find('.product_row').length <= 0) {
                console.log('BIR Payment Interceptor: No products, letting original handler deal with it');
                return; // Let original handler deal with this
            }
            
            console.log('BIR Payment Interceptor: Products found, checking for BIR modal function');
            
            // Show BIR modal
            if (typeof window.showBIRReceiptModal === 'function') {
                console.log('BIR Payment Interceptor: Showing BIR modal for cash payment');
                e.preventDefault();
                e.stopImmediatePropagation();
                window.showBIRReceiptModal('cash');
            } else {
                console.log('BIR Payment Interceptor: showBIRReceiptModal function not found');
            }
        });
        
        // Intercept Multiple Pay button clicks
        multiPayButton.off('click.bir').on('click.bir', function(e) {
            console.log('BIR Payment Interceptor: Multi-pay button clicked');
            
            // Check if products are present
            if ($('table#pos_table tbody').find('.product_row').length <= 0) {
                console.log('BIR Payment Interceptor: No products, letting original handler deal with it');
                return; // Let original handler deal with this
            }
            
            console.log('BIR Payment Interceptor: Products found, checking for BIR modal function');
            
            // Show BIR modal
            if (typeof window.showBIRReceiptModal === 'function') {
                console.log('BIR Payment Interceptor: Showing BIR modal for multi-pay');
                e.preventDefault();
                e.stopImmediatePropagation();
                window.showBIRReceiptModal('multi_pay');
            } else {
                console.log('BIR Payment Interceptor: showBIRReceiptModal function not found');
            }
        });
        
        console.log('BIR Payment Interceptor initialized successfully');
    }
    
    // Try to initialize immediately
    initializeInterceptors();
    
    // Also try after a delay in case the buttons are loaded dynamically
    setTimeout(initializeInterceptors, 1000);
    setTimeout(initializeInterceptors, 3000);
    });
}

// Start waiting for jQuery
waitForJQuery();
