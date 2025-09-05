<?php

namespace Modules\BIRReceiptPlugin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BIRReceiptCustomization extends Model
{
    use HasFactory;

    protected $table = 'bir_receipt_customizations';
    
    // Override getTable to ensure correct table name
    public function getTable()
    {
        return 'bir_receipt_customizations';
    }

    protected $fillable = [
        'business_id',
        'template_code',
        'customization_name',
        'description',
        'layout_settings',
        'field_settings',
        'style_settings',
        'custom_css',
        'custom_js',
        'is_active',
        'is_default',
    ];

    protected $casts = [
        'layout_settings' => 'array',
        'field_settings' => 'array',
        'style_settings' => 'array',
        'is_active' => 'boolean',
        'is_default' => 'boolean',
    ];

    /**
     * Get customizations for a specific business and template
     */
    public static function getForBusinessAndTemplate($businessId, $templateCode)
    {
        return self::where('business_id', $businessId)
                   ->where('template_code', $templateCode)
                   ->where('is_active', true)
                   ->orderBy('is_default', 'desc')
                   ->get();
    }

    /**
     * Get default customization for a template
     */
    public static function getDefaultForTemplate($templateCode, $businessId = null)
    {
        return self::where('template_code', $templateCode)
                   ->where('business_id', $businessId)
                   ->where('is_default', true)
                   ->where('is_active', true)
                   ->first();
    }

    /**
     * Get layout settings as array
     */
    public function getLayoutSettingsAttribute()
    {
        return $this->layout_settings ?? [];
    }

    /**
     * Set layout settings
     */
    public function setLayoutSettingsAttribute($value)
    {
        $this->layout_settings = is_array($value) ? $value : json_decode($value, true);
    }

    /**
     * Get field settings as array
     */
    public function getFieldSettingsAttribute()
    {
        return $this->field_settings ?? [];
    }

    /**
     * Set field settings
     */
    public function setFieldSettingsAttribute($value)
    {
        $this->field_settings = is_array($value) ? $value : json_decode($value, true);
    }

    /**
     * Get style settings as array
     */
    public function getStyleSettingsAttribute()
    {
        return $this->style_settings ?? [];
    }

    /**
     * Set style settings
     */
    public function setStyleSettingsAttribute($value)
    {
        $this->style_settings = is_array($value) ? $value : json_decode($value, true);
    }
}
