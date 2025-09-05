/**
 * BIR Receipt Plugin Integration
 * This file integrates the BIR Receipt Plugin with UltimatePOS
 */

(function() {
    'use strict';

    // Wait for DOM to be ready
    document.addEventListener('DOMContentLoaded', function() {
        initializeBIRIntegration();
    });

    function initializeBIRIntegration() {
        // Add BIR Receipt button to POS interface
        addBIRReceiptButton();
        
        // Add BIR Receipt menu item
        addBIRReceiptMenuItem();
        
        // Listen for sale completion events
        listenForSaleCompletion();
    }

    function addBIRReceiptButton() {
        // Find the POS interface container
        const posContainer = document.querySelector('.pos-container') || 
                           document.querySelector('#pos-container') ||
                           document.querySelector('.sale-pos-container');
        
        if (posContainer) {
            // Create BIR Receipt button
            const birButton = document.createElement('button');
            birButton.id = 'bir-receipt-btn';
            birButton.className = 'btn btn-success btn-sm';
            birButton.innerHTML = '<i class="fas fa-receipt"></i> BIR Receipt';
            birButton.style.marginLeft = '10px';
            birButton.onclick = generateBIRReceipt;
            
            // Find existing print button and add BIR button after it
            const printButton = posContainer.querySelector('.print-receipt-btn') ||
                              posContainer.querySelector('[onclick*="print"]') ||
                              posContainer.querySelector('.btn-print');
            
            if (printButton) {
                printButton.parentNode.insertBefore(birButton, printButton.nextSibling);
            } else {
                // Add to a suitable location
                const buttonContainer = posContainer.querySelector('.btn-group') ||
                                      posContainer.querySelector('.action-buttons') ||
                                      posContainer.querySelector('.pos-actions');
                
                if (buttonContainer) {
                    buttonContainer.appendChild(birButton);
                }
            }
        }
    }

    function addBIRReceiptMenuItem() {
        // Add BIR Receipt menu item to the main navigation
        const mainMenu = document.querySelector('.main-sidebar .nav-sidebar') ||
                        document.querySelector('.sidebar-menu') ||
                        document.querySelector('.nav-sidebar');
        
        if (mainMenu) {
            const birMenuItem = document.createElement('li');
            birMenuItem.className = 'nav-item';
            birMenuItem.innerHTML = `
                <a href="/bir-receipt" class="nav-link">
                    <i class="fas fa-receipt nav-icon"></i>
                    <p>BIR Receipt Plugin</p>
                </a>
            `;
            
            mainMenu.appendChild(birMenuItem);
        }
    }

    function listenForSaleCompletion() {
        // Listen for sale completion events
        document.addEventListener('saleCompleted', function(event) {
            const transactionData = event.detail;
            if (transactionData && transactionData.transaction_id) {
                // Store transaction data for BIR receipt generation
                window.lastTransactionData = transactionData;
                
                // Enable BIR Receipt button
                const birButton = document.getElementById('bir-receipt-btn');
                if (birButton) {
                    birButton.disabled = false;
                    birButton.classList.remove('btn-secondary');
                    birButton.classList.add('btn-success');
                }
            }
        });
    }

    function generateBIRReceipt() {
        // Get transaction data
        const transactionData = window.lastTransactionData || getCurrentTransactionData();
        
        if (!transactionData) {
            alert('No transaction data available. Please complete a sale first.');
            return;
        }

        // Show template selection modal
        showTemplateSelectionModal(transactionData);
    }

    function getCurrentTransactionData() {
        // Try to extract transaction data from the current POS interface
        const transactionId = document.querySelector('[name="transaction_id"]')?.value ||
                            document.querySelector('#transaction_id')?.value ||
                            getTransactionIdFromURL();
        
        if (transactionId) {
            return {
                transaction_id: transactionId,
                customer_name: document.querySelector('[name="customer_name"]')?.value || 'Walk-in Customer',
                items: getCurrentCartItems(),
                total: getCurrentTotal()
            };
        }
        
        return null;
    }

    function getTransactionIdFromURL() {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get('transaction_id') || urlParams.get('id');
    }

    function getCurrentCartItems() {
        // Extract items from the current cart
        const cartItems = [];
        const itemRows = document.querySelectorAll('.cart-item, .sale-item, .pos-item');
        
        itemRows.forEach(row => {
            const name = row.querySelector('.item-name, .product-name')?.textContent || 'Item';
            const quantity = row.querySelector('.quantity, .qty')?.textContent || '1';
            const price = row.querySelector('.price, .amount')?.textContent || '0';
            
            cartItems.push({
                name: name.trim(),
                quantity: parseInt(quantity) || 1,
                price: parseFloat(price.replace(/[^\d.-]/g, '')) || 0,
                total: (parseInt(quantity) || 1) * (parseFloat(price.replace(/[^\d.-]/g, '')) || 0)
            });
        });
        
        return cartItems;
    }

    function getCurrentTotal() {
        const totalElement = document.querySelector('.total-amount, .grand-total, .final-total');
        if (totalElement) {
            return parseFloat(totalElement.textContent.replace(/[^\d.-]/g, '')) || 0;
        }
        return 0;
    }

    function showTemplateSelectionModal(transactionData) {
        // Create modal for template selection
        const modal = document.createElement('div');
        modal.className = 'modal fade';
        modal.id = 'bir-template-modal';
        modal.innerHTML = `
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Select BIR Receipt Template</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="bir-template-select">Template:</label>
                            <select class="form-control" id="bir-template-select">
                                <option value="A1">A1 - Official Receipt (OR)</option>
                                <option value="A2">A2 - Sales Invoice (SI)</option>
                                <option value="A3">A3 - Cash Invoice (CI)</option>
                                <option value="A4">A4 - Charge Invoice (ChI)</option>
                                <option value="A5">A5 - Credit Memo (CM)</option>
                                <option value="A6">A6 - Debit Memo (DM)</option>
                                <option value="B1">B1 - Official Receipt (OR) - Service</option>
                                <option value="B2">B2 - Sales Invoice (SI) - Service</option>
                                <option value="B3">B3 - Cash Invoice (CI) - Service</option>
                                <option value="B4">B4 - Charge Invoice (ChI) - Service</option>
                                <option value="B5">B5 - Credit Memo (CM) - Service</option>
                                <option value="B6">B6 - Debit Memo (DM) - Service</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="bir-format-select">Format:</label>
                            <select class="form-control" id="bir-format-select">
                                <option value="html">HTML Preview</option>
                                <option value="pdf">PDF Download</option>
                                <option value="print">Print</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" onclick="generateBIRReceiptWithTemplate()">
                            Generate BIR Receipt
                        </button>
                    </div>
                </div>
            </div>
        `;
        
        document.body.appendChild(modal);
        
        // Show modal
        $(modal).modal('show');
        
        // Store transaction data globally for the modal
        window.birTransactionData = transactionData;
    }

    // Global function for the modal
    window.generateBIRReceiptWithTemplate = function() {
        const templateCode = document.getElementById('bir-template-select').value;
        const format = document.getElementById('bir-format-select').value;
        const transactionData = window.birTransactionData;
        
        // Close modal
        $('#bir-template-modal').modal('hide');
        
        // Generate BIR receipt
        generateBIRReceiptRequest(transactionData, templateCode, format);
    };

    function generateBIRReceiptRequest(transactionData, templateCode, format) {
        // Show loading indicator
        showLoadingIndicator();
        
        // Prepare request data
        const requestData = {
            transaction_id: transactionData.transaction_id,
            template_code: templateCode,
            format: format,
            customer_name: transactionData.customer_name || 'Walk-in Customer',
            customer_address: transactionData.customer_address || '',
            customer_tin: transactionData.customer_tin || '',
            items: transactionData.items || [],
            subtotal: transactionData.subtotal || 0,
            vat_amount: transactionData.vat_amount || 0,
            total_amount: transactionData.total || 0
        };
        
        // Make AJAX request
        fetch('/bir-receipt/generate', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(requestData)
        })
        .then(response => {
            hideLoadingIndicator();
            
            if (format === 'pdf') {
                // Handle PDF download
                return response.blob();
            } else {
                // Handle HTML response
                return response.text();
            }
        })
        .then(data => {
            if (format === 'pdf') {
                // Download PDF
                const blob = new Blob([data], { type: 'application/pdf' });
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = `bir_receipt_${transactionData.transaction_id}.pdf`;
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                window.URL.revokeObjectURL(url);
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
            hideLoadingIndicator();
            console.error('Error generating BIR receipt:', error);
            alert('Error generating BIR receipt. Please try again.');
        });
    }

    function showReceiptPreview(htmlContent) {
        // Create preview modal
        const previewModal = document.createElement('div');
        previewModal.className = 'modal fade';
        previewModal.id = 'bir-preview-modal';
        previewModal.innerHTML = `
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">BIR Receipt Preview</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="bir-receipt-preview">${htmlContent}</div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" onclick="printBIRReceipt()">
                            <i class="fas fa-print"></i> Print
                        </button>
                    </div>
                </div>
            </div>
        `;
        
        document.body.appendChild(previewModal);
        $(previewModal).modal('show');
    }

    function showLoadingIndicator() {
        // Show loading indicator
        const loadingDiv = document.createElement('div');
        loadingDiv.id = 'bir-loading';
        loadingDiv.className = 'bir-loading-overlay';
        loadingDiv.innerHTML = `
            <div class="bir-loading-content">
                <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">Generating BIR Receipt...</span>
                </div>
                <p>Generating BIR Receipt...</p>
            </div>
        `;
        loadingDiv.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
        `;
        document.body.appendChild(loadingDiv);
    }

    function hideLoadingIndicator() {
        const loadingDiv = document.getElementById('bir-loading');
        if (loadingDiv) {
            loadingDiv.remove();
        }
    }

    // Global print function
    window.printBIRReceipt = function() {
        const previewContent = document.getElementById('bir-receipt-preview');
        if (previewContent) {
            const printWindow = window.open('', '_blank');
            printWindow.document.write(previewContent.innerHTML);
            printWindow.document.close();
            printWindow.print();
        }
    };

    // Add CSS styles
    const style = document.createElement('style');
    style.textContent = `
        .bir-loading-content {
            text-align: center;
            color: white;
        }
        .bir-loading-content p {
            margin-top: 10px;
            font-size: 16px;
        }
        #bir-receipt-btn {
            transition: all 0.3s ease;
        }
        #bir-receipt-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
    `;
    document.head.appendChild(style);

})();
