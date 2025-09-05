@extends('layouts.app')

@section('title', 'BIR Receipt Plugin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-receipt"></i> BIR Receipt Plugin
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('bir-receipt.settings') }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-cog"></i> Settings
                        </a>
                    </div>
                </div>
                
                <div class="card-body">
                    <!-- Plugin Status -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="alert alert-info">
                                <h5><i class="fas fa-info-circle"></i> BIR Receipt Plugin Status</h5>
                                <p>This plugin provides BIR-accredited receipt generation with customizable templates following RMC No. 77-2024 Annex A1-B6.</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Available Templates -->
                    <div class="row">
                        <div class="col-md-8">
                            <h4>Available BIR Receipt Templates</h4>
                            <div class="row">
                                @foreach($templates as $template)
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="card">
                                        <div class="card-body">
                                            <h6 class="card-title">{{ $template->template_name }}</h6>
                                            <p class="card-text small">{{ $template->description }}</p>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('bir-receipt.customize', $template->template_code) }}" 
                                                   class="btn btn-outline-primary">
                                                    <i class="fas fa-edit"></i> Customize
                                                </a>
                                                <button type="button" class="btn btn-outline-success" 
                                                        onclick="previewTemplate('{{ $template->template_code }}')">
                                                    <i class="fas fa-eye"></i> Preview
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <h4>Quick Actions</h4>
                            <div class="list-group">
                                <a href="{{ route('bir-receipt.settings') }}" class="list-group-item list-group-item-action">
                                    <i class="fas fa-cog"></i> Configure BIR Settings
                                </a>
                                <a href="#" class="list-group-item list-group-item-action" onclick="generateTestReceipt()">
                                    <i class="fas fa-print"></i> Generate Test Receipt
                                </a>
                                <a href="#" class="list-group-item list-group-item-action" onclick="showIntegrationGuide()">
                                    <i class="fas fa-code"></i> Integration Guide
                                </a>
                            </div>
                            
                            <!-- Current Settings Summary -->
                            @if($settings)
                            <div class="mt-4">
                                <h5>Current Settings</h5>
                                <div class="card">
                                    <div class="card-body">
                                        <p><strong>Business:</strong> {{ $settings->business_name }}</p>
                                        <p><strong>TIN:</strong> {{ $settings->tin_number }}</p>
                                        <p><strong>Default Template:</strong> {{ $settings->default_template }}</p>
                                        <p><strong>Status:</strong> 
                                            <span class="badge badge-success">Active</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Receipt Preview</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="previewContent">
                    <div class="text-center">
                        <div class="spinner-border" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="printPreview()">
                    <i class="fas fa-print"></i> Print
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript')
<script>
function previewTemplate(templateCode) {
    $('#previewModal').modal('show');
    
    // Generate test receipt data
    const testData = {
        transaction_id: 'TEST-' + Date.now(),
        template_code: templateCode,
        format: 'html'
    };
    
    $.ajax({
        url: '{{ route("bir-receipt.generate") }}',
        method: 'POST',
        data: testData,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            $('#previewContent').html(response);
        },
        error: function(xhr) {
            $('#previewContent').html('<div class="alert alert-danger">Error loading preview</div>');
        }
    });
}

function generateTestReceipt() {
    const templateCode = '{{ $settings->default_template ?? "A1" }}';
    previewTemplate(templateCode);
}

function printPreview() {
    const printContent = document.getElementById('previewContent').innerHTML;
    const originalContent = document.body.innerHTML;
    
    document.body.innerHTML = printContent;
    window.print();
    document.body.innerHTML = originalContent;
    
    // Reload the page to restore functionality
    location.reload();
}

function showIntegrationGuide() {
    alert('Integration Guide:\n\n1. Add BIR Receipt button to your POS interface\n2. Call the generate endpoint with transaction data\n3. Customize templates as needed\n4. Configure BIR settings for your business');
}
</script>
@endsection
