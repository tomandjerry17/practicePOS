<?php

namespace Modules\BIRReceiptPlugin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BIRReceiptSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'tin_number',
        'business_name',
        'business_address',
        'business_phone',
        'business_email',
        'business_website',
        'logo_path',
        'header_text',
        'footer_text',
        'default_template',
        'custom_fields',
        'receipt_settings',
        'is_active',
    ];

    protected $casts = [
        'custom_fields' => 'array',
        'receipt_settings' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get settings for a specific business
     */
    public static function getForBusiness($businessId = null)
    {
        return self::where('business_id', $businessId)->where('is_active', true)->first();
    }

    /**
     * Get default settings
     */
    public static function getDefault()
    {
        return self::where('business_id', null)->where('is_active', true)->first();
    }

    /**
     * Get receipt settings as array
     */
    public function getReceiptSettingsAttribute()
    {
        return $this->receipt_settings ?? [];
    }

    /**
     * Set receipt settings
     */
    public function setReceiptSettingsAttribute($value)
    {
        $this->receipt_settings = is_array($value) ? $value : json_decode($value, true);
    }

    /**
     * Get custom fields as array
     */
    public function getCustomFieldsAttribute()
    {
        return $this->custom_fields ?? [];
    }

    /**
     * Set custom fields
     */
    public function setCustomFieldsAttribute($value)
    {
        $this->custom_fields = is_array($value) ? $value : json_decode($value, true);
    }
}
