/**
 * BIR Receipt Plugin Integration - Debug Version
 */

console.log('BIR Receipt Plugin: Script loading...');

// Wait for DOM to be ready
document.addEventListener('DOMContentLoaded', function() {
    console.log('BIR Receipt Plugin: DOM ready, initializing...');
    initializeBIRIntegration();
});

function initializeBIRIntegration() {
    console.log('BIR Receipt Plugin: Initializing integration...');
    
    // Add click handler to BIR Receipt button
    const birButton = document.getElementById('bir-receipt-btn');
    console.log('BIR Receipt Plugin: Looking for button with ID bir-receipt-btn');
    console.log('BIR Receipt Plugin: Button found:', birButton);
    
    if (birButton) {
        console.log('BIR Receipt Plugin: Adding click event listener...');
        birButton.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('BIR Receipt Plugin: Button clicked!');
            alert('BIR Receipt button clicked! This is working.');
            generateBIRReceipt();
        });
        console.log('BIR Receipt Plugin: Event listener added successfully');
    } else {
        console.log('BIR Receipt Plugin: Button not found, trying alternative selectors...');
        
        // Try alternative selectors
        const altSelectors = [
            '[id*="bir-receipt"]',
            '[title*="BIR Receipt"]',
            'button:contains("BIR Receipt")'
        ];
        
        for (let selector of altSelectors) {
            const altButton = document.querySelector(selector);
            if (altButton) {
                console.log('BIR Receipt Plugin: Found button with selector:', selector);
                altButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    console.log('BIR Receipt Plugin: Alternative button clicked!');
                    alert('BIR Receipt button clicked! This is working.');
                    generateBIRReceipt();
                });
                break;
            }
        }
    }
}

function generateBIRReceipt() {
    console.log('BIR Receipt Plugin: generateBIRReceipt called');
    
    // Simple test - just show an alert for now
    alert('BIR Receipt generation started! Check console for details.');
    
    // Get current transaction data
    const transactionData = getCurrentTransactionData();
    console.log('BIR Receipt Plugin: Transaction data:', transactionData);
    
    if (!transactionData) {
        alert('No transaction data available. Please add items to cart first.');
        return;
    }

    // Show template selection modal
    showTemplateSelectionModal(transactionData);
}

function getCurrentTransactionData() {
    console.log('BIR Receipt Plugin: Getting transaction data...');
    
    // Get transaction ID from URL or generate one
    const transactionId = getTransactionIdFromURL() || generateTransactionId();
    console.log('BIR Receipt Plugin: Transaction ID:', transactionId);
    
    // Get customer information
    const customerName = getElementValue('customer_name') || 'Walk-in Customer';
    const customerPhone = getElementValue('customer_phone') || '';
    const customerAddress = getElementValue('customer_address') || '';
    
    // Get cart items
    const items = getCartItems();
    console.log('BIR Receipt Plugin: Cart items:', items);
    
    // Get totals
    const subtotal = parseFloat(getElementText('subtotal').replace(/[^\d.-]/g, '')) || 0;
    const taxAmount = parseFloat(getElementText('tax_amount').replace(/[^\d.-]/g, '')) || 0;
    const totalAmount = parseFloat(getElementText('total_payable').replace(/[^\d.-]/g, '')) || 0;
    
    console.log('BIR Receipt Plugin: Totals - Subtotal:', subtotal, 'Tax:', taxAmount, 'Total:', totalAmount);
    
    if (items.length === 0) {
        console.log('BIR Receipt Plugin: No items found in cart');
        return null;
    }
    
    return {
        transaction_id: transactionId,
        customer_name: customerName,
        customer_phone: customerPhone,
        customer_address: customerAddress,
        items: items,
        subtotal: subtotal,
        tax_amount: taxAmount,
        total_amount: totalAmount,
        date: new Date().toISOString().split('T')[0],
        time: new Date().toTimeString().split(' ')[0]
    };
}

function getElementValue(selector) {
    const element = document.getElementById(selector) || document.querySelector(`[name="${selector}"]`);
    return element ? element.value : '';
}

function getElementText(selector) {
    const element = document.getElementById(selector) || document.querySelector(`.${selector}`);
    return element ? element.textContent : '';
}

function getTransactionIdFromURL() {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get('transaction_id') || urlParams.get('id');
}

function generateTransactionId() {
    return 'POS_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
}

function getCartItems() {
    console.log('BIR Receipt Plugin: Getting cart items...');
    const items = [];
    
    // Try different selectors for cart items
    const itemSelectors = [
        '.cart-item',
        '.sale-item', 
        '.pos-item',
        'tr[data-item-id]',
        '.item-row',
        'tbody tr'
    ];
    
    let itemRows = [];
    for (let selector of itemSelectors) {
        itemRows = document.querySelectorAll(selector);
        if (itemRows.length > 0) {
            console.log(`BIR Receipt Plugin: Found ${itemRows.length} items with selector: ${selector}`);
            break;
        }
    }
    
    itemRows.forEach(function(row, index) {
        // Try different selectors for item details
        const name = getItemText(row, ['.item-name', '.product-name', '.name', '[data-name]', 'td:first-child']) || `Item ${index + 1}`;
        const quantity = getItemText(row, ['.quantity', '.qty', '[data-quantity]', 'td:nth-child(2)']) || '1';
        const price = getItemText(row, ['.price', '.amount', '.unit-price', '[data-price]', 'td:nth-child(3)']) || '0';
        const total = getItemText(row, ['.total', '.line-total', '[data-total]', 'td:last-child']) || '0';
        
        console.log(`BIR Receipt Plugin: Item ${index + 1} - Name: ${name}, Qty: ${quantity}, Price: ${price}, Total: ${total}`);
        
        if (name && name !== `Item ${index + 1}`) {
            items.push({
                name: name.trim(),
                quantity: parseInt(quantity) || 1,
                unit_price: parseFloat(price.replace(/[^\d.-]/g, '')) || 0,
                total: parseFloat(total.replace(/[^\d.-]/g, '')) || 0
            });
        }
    });
    
    console.log('BIR Receipt Plugin: Final cart items:', items);
    return items;
}

function getItemText(row, selectors) {
    for (let selector of selectors) {
        const element = row.querySelector(selector);
        if (element) {
            return element.textContent.trim();
        }
    }
    return '';
}

function showTemplateSelectionModal(transactionData) {
    console.log('BIR Receipt Plugin: Showing template selection modal');
    
    // Simple modal for testing
    const templateCode = prompt('Enter template code (A1, A2, A3, etc.):', 'A1');
    if (!templateCode) return;
    
    const format = prompt('Enter format (html, pdf, print):', 'html');
    if (!format) return;
    
    console.log('BIR Receipt Plugin: Generating receipt with template:', templateCode, 'format:', format);
    
    // Generate BIR receipt
    generateBIRReceiptRequest(transactionData, templateCode, format);
}

function generateBIRReceiptRequest(transactionData, templateCode, format) {
    console.log('BIR Receipt Plugin: Making request to backend...');
    
    // Prepare request data
    const requestData = {
        transaction_id: transactionData.transaction_id,
        template_code: templateCode,
        format: format,
        customer_name: transactionData.customer_name,
        customer_phone: transactionData.customer_phone,
        customer_address: transactionData.customer_address,
        items: transactionData.items,
        subtotal: transactionData.subtotal,
        tax_amount: transactionData.tax_amount,
        total_amount: transactionData.total_amount,
        date: transactionData.date,
        time: transactionData.time
    };
    
    console.log('BIR Receipt Plugin: Request data:', requestData);
    
    // Get CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    const token = csrfToken ? csrfToken.getAttribute('content') : '';
    console.log('BIR Receipt Plugin: CSRF token:', token);
    
    // Make fetch request
    fetch('/bir-receipt/generate', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json'
        },
        body: JSON.stringify(requestData)
    })
    .then(response => {
        console.log('BIR Receipt Plugin: Response received:', response.status, response.statusText);
        console.log('BIR Receipt Plugin: Response headers:', response.headers);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        if (format === 'pdf') {
            return response.blob();
        } else {
            return response.text();
        }
    })
    .then(data => {
        console.log('BIR Receipt Plugin: Data received:', data);
        
        if (format === 'pdf') {
            // Handle PDF download
            const blob = new Blob([data], { type: 'application/pdf' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `bir_receipt_${transactionData.transaction_id}.pdf`;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            window.URL.revokeObjectURL(url);
            alert('PDF downloaded successfully!');
        } else if (format === 'print') {
            // Open print window
            const printWindow = window.open('', '_blank');
            printWindow.document.write(data);
            printWindow.document.close();
            printWindow.print();
        } else {
            // Show HTML preview
            showReceiptPreview(data);
        }
    })
    .catch(error => {
        console.error('BIR Receipt Plugin: Error:', error);
        alert('Error generating BIR receipt: ' + error.message);
    });
}

function showReceiptPreview(htmlContent) {
    console.log('BIR Receipt Plugin: Showing preview');
    
    // Simple preview - just show in a new window
    const previewWindow = window.open('', '_blank', 'width=800,height=600');
    previewWindow.document.write(htmlContent);
    previewWindow.document.close();
}

console.log('BIR Receipt Plugin: Script loaded successfully');