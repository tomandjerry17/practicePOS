@extends('layouts.app')

@section('title', 'BIR Receipt Settings')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-cog"></i> BIR Receipt Settings
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('bir-receipt.index') }}" class="btn btn-sm btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Plugin
                        </a>
                    </div>
                </div>
                
                <div class="card-body">
                    <form id="birSettingsForm">
                        @csrf
                        
                        <!-- Business Information -->
                        <div class="row">
                            <div class="col-md-6">
                                <h4>Business Information</h4>
                                
                                <div class="form-group">
                                    <label for="tin_number">TIN Number *</label>
                                    <input type="text" class="form-control" id="tin_number" name="tin_number" 
                                           value="{{ $settings->tin_number ?? '' }}" required>
                                    <small class="form-text text-muted">Tax Identification Number</small>
                                </div>
                                
                                <div class="form-group">
                                    <label for="business_name">Business Name *</label>
                                    <input type="text" class="form-control" id="business_name" name="business_name" 
                                           value="{{ $settings->business_name ?? '' }}" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="business_address">Business Address *</label>
                                    <textarea class="form-control" id="business_address" name="business_address" 
                                              rows="3" required>{{ $settings->business_address ?? '' }}</textarea>
                                </div>
                                
                                <div class="form-group">
                                    <label for="business_phone">Business Phone</label>
                                    <input type="text" class="form-control" id="business_phone" name="business_phone" 
                                           value="{{ $settings->business_phone ?? '' }}">
                                </div>
                                
                                <div class="form-group">
                                    <label for="business_email">Business Email</label>
                                    <input type="email" class="form-control" id="business_email" name="business_email" 
                                           value="{{ $settings->business_email ?? '' }}">
                                </div>
                                
                                <div class="form-group">
                                    <label for="business_website">Business Website</label>
                                    <input type="url" class="form-control" id="business_website" name="business_website" 
                                           value="{{ $settings->business_website ?? '' }}">
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <h4>Receipt Configuration</h4>
                                
                                <div class="form-group">
                                    <label for="default_template">Default Template *</label>
                                    <select class="form-control" id="default_template" name="default_template" required>
                                        <option value="A1" {{ ($settings->default_template ?? '') == 'A1' ? 'selected' : '' }}>A1 - Official Receipt (OR)</option>
                                        <option value="A2" {{ ($settings->default_template ?? '') == 'A2' ? 'selected' : '' }}>A2 - Sales Invoice (SI)</option>
                                        <option value="A3" {{ ($settings->default_template ?? '') == 'A3' ? 'selected' : '' }}>A3 - Cash Invoice (CI)</option>
                                        <option value="A4" {{ ($settings->default_template ?? '') == 'A4' ? 'selected' : '' }}>A4 - Charge Invoice (ChI)</option>
                                        <option value="A5" {{ ($settings->default_template ?? '') == 'A5' ? 'selected' : '' }}>A5 - Credit Memo (CM)</option>
                                        <option value="A6" {{ ($settings->default_template ?? '') == 'A6' ? 'selected' : '' }}>A6 - Debit Memo (DM)</option>
                                        <option value="B1" {{ ($settings->default_template ?? '') == 'B1' ? 'selected' : '' }}>B1 - Official Receipt (OR) - Service</option>
                                        <option value="B2" {{ ($settings->default_template ?? '') == 'B2' ? 'selected' : '' }}>B2 - Sales Invoice (SI) - Service</option>
                                        <option value="B3" {{ ($settings->default_template ?? '') == 'B3' ? 'selected' : '' }}>B3 - Cash Invoice (CI) - Service</option>
                                        <option value="B4" {{ ($settings->default_template ?? '') == 'B4' ? 'selected' : '' }}>B4 - Charge Invoice (ChI) - Service</option>
                                        <option value="B5" {{ ($settings->default_template ?? '') == 'B5' ? 'selected' : '' }}>B5 - Credit Memo (CM) - Service</option>
                                        <option value="B6" {{ ($settings->default_template ?? '') == 'B6' ? 'selected' : '' }}>B6 - Debit Memo (DM) - Service</option>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label for="logo_path">Logo Path</label>
                                    <input type="text" class="form-control" id="logo_path" name="logo_path" 
                                           value="{{ $settings->logo_path ?? '' }}">
                                    <small class="form-text text-muted">Path to your business logo image</small>
                                </div>
                                
                                <div class="form-group">
                                    <label for="header_text">Header Text</label>
                                    <textarea class="form-control" id="header_text" name="header_text" 
                                              rows="3">{{ $settings->header_text ?? '' }}</textarea>
                                    <small class="form-text text-muted">Additional text to display at the top of receipts</small>
                                </div>
                                
                                <div class="form-group">
                                    <label for="footer_text">Footer Text</label>
                                    <textarea class="form-control" id="footer_text" name="footer_text" 
                                              rows="3">{{ $settings->footer_text ?? '' }}</textarea>
                                    <small class="form-text text-muted">Additional text to display at the bottom of receipts</small>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Receipt Settings -->
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <h4>Receipt Format Settings</h4>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="font_size">Font Size</label>
                                            <select class="form-control" id="font_size" name="receipt_settings[font_size]">
                                                <option value="10" {{ ($settings->receipt_settings['font_size'] ?? '') == '10' ? 'selected' : '' }}>10px</option>
                                                <option value="11" {{ ($settings->receipt_settings['font_size'] ?? '') == '11' ? 'selected' : '' }}>11px</option>
                                                <option value="12" {{ ($settings->receipt_settings['font_size'] ?? '') == '12' ? 'selected' : '' }}>12px</option>
                                                <option value="13" {{ ($settings->receipt_settings['font_size'] ?? '') == '13' ? 'selected' : '' }}>13px</option>
                                                <option value="14" {{ ($settings->receipt_settings['font_size'] ?? '') == '14' ? 'selected' : '' }}>14px</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="paper_width">Paper Width</label>
                                            <select class="form-control" id="paper_width" name="receipt_settings[paper_width]">
                                                <option value="80mm" {{ ($settings->receipt_settings['paper_width'] ?? '') == '80mm' ? 'selected' : '' }}>80mm</option>
                                                <option value="58mm" {{ ($settings->receipt_settings['paper_width'] ?? '') == '58mm' ? 'selected' : '' }}>58mm</option>
                                                <option value="A4" {{ ($settings->receipt_settings['paper_width'] ?? '') == 'A4' ? 'selected' : '' }}>A4</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="margin">Margin</label>
                                            <input type="number" class="form-control" id="margin" name="receipt_settings[margin]" 
                                                   value="{{ $settings->receipt_settings['margin'] ?? '10' }}" min="0" max="50">
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="currency_symbol">Currency Symbol</label>
                                            <input type="text" class="form-control" id="currency_symbol" name="receipt_settings[currency_symbol]" 
                                                   value="{{ $settings->receipt_settings['currency_symbol'] ?? '₱' }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Custom Fields -->
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <h4>Custom Fields</h4>
                                <div id="customFields">
                                    @if($settings && $settings->custom_fields)
                                        @foreach($settings->custom_fields as $key => $field)
                                        <div class="row custom-field-row">
                                            <div class="col-md-4">
                                                <input type="text" class="form-control" name="custom_fields[{{ $key }}][label]" 
                                                       value="{{ $field['label'] ?? '' }}" placeholder="Field Label">
                                            </div>
                                            <div class="col-md-4">
                                                <input type="text" class="form-control" name="custom_fields[{{ $key }}][value]" 
                                                       value="{{ $field['value'] ?? '' }}" placeholder="Field Value">
                                            </div>
                                            <div class="col-md-2">
                                                <select class="form-control" name="custom_fields[{{ $key }}][position]">
                                                    <option value="header" {{ ($field['position'] ?? '') == 'header' ? 'selected' : '' }}>Header</option>
                                                    <option value="footer" {{ ($field['position'] ?? '') == 'footer' ? 'selected' : '' }}>Footer</option>
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <button type="button" class="btn btn-sm btn-danger" onclick="removeCustomField(this)">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                        @endforeach
                                    @endif
                                </div>
                                <button type="button" class="btn btn-sm btn-primary mt-2" onclick="addCustomField()">
                                    <i class="fas fa-plus"></i> Add Custom Field
                                </button>
                            </div>
                        </div>
                        
                        <!-- Save Button -->
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Save Settings
                                </button>
                                <button type="button" class="btn btn-secondary" onclick="testReceipt()">
                                    <i class="fas fa-print"></i> Test Receipt
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript')
<script>
let customFieldIndex = {{ $settings && $settings->custom_fields ? count($settings->custom_fields) : 0 }};

$('#birSettingsForm').on('submit', function(e) {
    e.preventDefault();
    
    $.ajax({
        url: '{{ route("bir-receipt.save-settings") }}',
        method: 'POST',
        data: $(this).serialize(),
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                toastr.success('Settings saved successfully!');
            }
        },
        error: function(xhr) {
            toastr.error('Error saving settings');
        }
    });
});

function addCustomField() {
    const html = `
        <div class="row custom-field-row mt-2">
            <div class="col-md-4">
                <input type="text" class="form-control" name="custom_fields[${customFieldIndex}][label]" placeholder="Field Label">
            </div>
            <div class="col-md-4">
                <input type="text" class="form-control" name="custom_fields[${customFieldIndex}][value]" placeholder="Field Value">
            </div>
            <div class="col-md-2">
                <select class="form-control" name="custom_fields[${customFieldIndex}][position]">
                    <option value="header">Header</option>
                    <option value="footer">Footer</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-sm btn-danger" onclick="removeCustomField(this)">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    `;
    
    $('#customFields').append(html);
    customFieldIndex++;
}

function removeCustomField(button) {
    $(button).closest('.custom-field-row').remove();
}

function testReceipt() {
    const templateCode = $('#default_template').val();
    window.open(`{{ route('bir-receipt.preview', '') }}/${templateCode}`, '_blank');
}
</script>
@endsection
