<?php

namespace Modules\BIRReceiptPlugin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BIRReceiptTemplate extends Model
{
    use HasFactory;

    protected $table = 'bir_receipt_templates';
    
    // Override getTable to ensure correct table name
    public function getTable()
    {
        return 'bir_receipt_templates';
    }

    protected $fillable = [
        'template_code',
        'template_name',
        'description',
        'template_content',
        'template_settings',
        'is_active',
        'is_default',
    ];

    protected $casts = [
        'template_settings' => 'array',
        'is_active' => 'boolean',
        'is_default' => 'boolean',
    ];

    /**
     * Get the default template
     */
    public static function getDefault()
    {
        return self::where('is_default', true)->where('is_active', true)->first();
    }

    /**
     * Get template by code
     */
    public static function getByCode($code)
    {
        return self::where('template_code', $code)->where('is_active', true)->first();
    }

    /**
     * Get all active templates
     */
    public static function getActive()
    {
        return self::where('is_active', true)->orderBy('template_code')->get();
    }

    /**
     * Get template settings as array
     */
    public function getSettingsAttribute()
    {
        return $this->template_settings ?? [];
    }

    /**
     * Set template settings
     */
    public function setSettingsAttribute($value)
    {
        $this->template_settings = is_array($value) ? $value : json_decode($value, true);
    }
}
