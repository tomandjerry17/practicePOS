@extends('layouts.app')

@section('title', 'Customize BIR Receipt Template')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-edit"></i> Customize Template: {{ $template->template_name }}
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('bir-receipt.index') }}" class="btn btn-sm btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Plugin
                        </a>
                    </div>
                </div>
                
                <div class="card-body">
                    <div class="row">
                        <!-- Customization Form -->
                        <div class="col-md-6">
                            <form id="customizationForm">
                                @csrf
                                
                                <div class="form-group">
                                    <label for="customization_name">Customization Name *</label>
                                    <input type="text" class="form-control" id="customization_name" name="customization_name" 
                                           value="{{ $customization->customization_name ?? 'Default Customization' }}" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea class="form-control" id="description" name="description" rows="3">{{ $customization->description ?? '' }}</textarea>
                                </div>
                                
                                <!-- Layout Settings -->
                                <h5>Layout Settings</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="header_height">Header Height (px)</label>
                                            <input type="number" class="form-control" id="header_height" name="layout_settings[header_height]" 
                                                   value="{{ $customization->layout_settings['header_height'] ?? '100' }}" min="50" max="300">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="footer_height">Footer Height (px)</label>
                                            <input type="number" class="form-control" id="footer_height" name="layout_settings[footer_height]" 
                                                   value="{{ $customization->layout_settings['footer_height'] ?? '80' }}" min="30" max="200">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="logo_size">Logo Size (px)</label>
                                            <input type="number" class="form-control" id="logo_size" name="layout_settings[logo_size]" 
                                                   value="{{ $customization->layout_settings['logo_size'] ?? '50' }}" min="20" max="150">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="business_name_size">Business Name Font Size (px)</label>
                                            <input type="number" class="form-control" id="business_name_size" name="layout_settings[business_name_size]" 
                                                   value="{{ $customization->layout_settings['business_name_size'] ?? '14' }}" min="8" max="24">
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Field Settings -->
                                <h5>Field Settings</h5>
                                <div class="form-group">
                                    <label>
                                        <input type="checkbox" name="field_settings[show_logo]" value="1" 
                                               {{ ($customization->field_settings['show_logo'] ?? true) ? 'checked' : '' }}>
                                        Show Logo
                                    </label>
                                </div>
                                
                                <div class="form-group">
                                    <label>
                                        <input type="checkbox" name="field_settings[show_business_name]" value="1" 
                                               {{ ($customization->field_settings['show_business_name'] ?? true) ? 'checked' : '' }}>
                                        Show Business Name
                                    </label>
                                </div>
                                
                                <div class="form-group">
                                    <label>
                                        <input type="checkbox" name="field_settings[show_address]" value="1" 
                                               {{ ($customization->field_settings['show_address'] ?? true) ? 'checked' : '' }}>
                                        Show Address
                                    </label>
                                </div>
                                
                                <div class="form-group">
                                    <label>
                                        <input type="checkbox" name="field_settings[show_phone]" value="1" 
                                               {{ ($customization->field_settings['show_phone'] ?? true) ? 'checked' : '' }}>
                                        Show Phone
                                    </label>
                                </div>
                                
                                <div class="form-group">
                                    <label>
                                        <input type="checkbox" name="field_settings[show_tin]" value="1" 
                                               {{ ($customization->field_settings['show_tin'] ?? true) ? 'checked' : '' }}>
                                        Show TIN
                                    </label>
                                </div>
                                
                                <div class="form-group">
                                    <label>
                                        <input type="checkbox" name="field_settings[show_bir_notice]" value="1" 
                                               {{ ($customization->field_settings['show_bir_notice'] ?? true) ? 'checked' : '' }}>
                                        Show BIR Notice
                                    </label>
                                </div>
                                
                                <!-- Style Settings -->
                                <h5>Style Settings</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="primary_color">Primary Color</label>
                                            <input type="color" class="form-control" id="primary_color" name="style_settings[primary_color]" 
                                                   value="{{ $customization->style_settings['primary_color'] ?? '#000000' }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="secondary_color">Secondary Color</label>
                                            <input type="color" class="form-control" id="secondary_color" name="style_settings[secondary_color]" 
                                                   value="{{ $customization->style_settings['secondary_color'] ?? '#666666' }}">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="border_style">Border Style</label>
                                    <select class="form-control" id="border_style" name="style_settings[border_style]">
                                        <option value="solid" {{ ($customization->style_settings['border_style'] ?? '') == 'solid' ? 'selected' : '' }}>Solid</option>
                                        <option value="dashed" {{ ($customization->style_settings['border_style'] ?? '') == 'dashed' ? 'selected' : '' }}>Dashed</option>
                                        <option value="dotted" {{ ($customization->style_settings['border_style'] ?? '') == 'dotted' ? 'selected' : '' }}>Dotted</option>
                                        <option value="none" {{ ($customization->style_settings['border_style'] ?? '') == 'none' ? 'selected' : '' }}>None</option>
                                    </select>
                                </div>
                                
                                <!-- Custom CSS -->
                                <h5>Custom CSS</h5>
                                <div class="form-group">
                                    <label for="custom_css">Additional CSS</label>
                                    <textarea class="form-control" id="custom_css" name="custom_css" rows="5" 
                                              placeholder="/* Add your custom CSS here */">{{ $customization->custom_css ?? '' }}</textarea>
                                </div>
                                
                                <!-- Custom JavaScript -->
                                <h5>Custom JavaScript</h5>
                                <div class="form-group">
                                    <label for="custom_js">Additional JavaScript</label>
                                    <textarea class="form-control" id="custom_js" name="custom_js" rows="5" 
                                              placeholder="// Add your custom JavaScript here">{{ $customization->custom_js ?? '' }}</textarea>
                                </div>
                                
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Save Customization
                                    </button>
                                    <button type="button" class="btn btn-secondary" onclick="previewCustomization()">
                                        <i class="fas fa-eye"></i> Preview
                                    </button>
                                </div>
                            </form>
                        </div>
                        
                        <!-- Live Preview -->
                        <div class="col-md-6">
                            <h5>Live Preview</h5>
                            <div id="previewContainer" style="border: 1px solid #ddd; padding: 10px; background: white; min-height: 400px;">
                                <div class="text-center">
                                    <div class="spinner-border" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                    <p>Loading preview...</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript')
<script>
// Load initial preview
$(document).ready(function() {
    previewCustomization();
});

$('#customizationForm').on('submit', function(e) {
    e.preventDefault();
    
    $.ajax({
        url: '{{ route("bir-receipt.save-customization", $template->template_code) }}',
        method: 'POST',
        data: $(this).serialize(),
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                toastr.success('Customization saved successfully!');
                previewCustomization();
            }
        },
        error: function(xhr) {
            toastr.error('Error saving customization');
        }
    });
});

// Auto-preview on form changes
$('#customizationForm input, #customizationForm select, #customizationForm textarea').on('change input', function() {
    clearTimeout(window.previewTimeout);
    window.previewTimeout = setTimeout(previewCustomization, 500);
});

function previewCustomization() {
    const formData = $('#customizationForm').serialize();
    
    $.ajax({
        url: '{{ route("bir-receipt.generate") }}',
        method: 'POST',
        data: formData + '&transaction_id=TEST-' + Date.now() + '&template_code={{ $template->template_code }}&format=html',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            $('#previewContainer').html(response);
        },
        error: function(xhr) {
            $('#previewContainer').html('<div class="alert alert-danger">Error loading preview</div>');
        }
    });
}
</script>
@endsection
