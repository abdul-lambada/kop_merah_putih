<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VillageSettings extends Model
{
    use HasFactory;

    protected $fillable = [
        'village_name',
        'village_code',
        'village_address',
        'village_phone',
        'village_email',
        'village_head',
        'logo_path',
        'description',
        'province',
        'regency',
        'district',
        'latitude',
        'longitude',
        'social_media',
        'is_active',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'social_media' => 'array',
        'is_active' => 'boolean',
    ];

    public function getLogoUrlAttribute()
    {
        if ($this->logo_path) {
            return asset('storage/' . $this->logo_path);
        }
        
        return asset('images/default-logo.png');
    }

    public function getFullAddressAttribute()
    {
        $address = [];
        
        if ($this->village_address) {
            $address[] = $this->village_address;
        }
        
        if ($this->district) {
            $address[] = 'Kec. ' . $this->district;
        }
        
        if ($this->regency) {
            $address[] = $this->regency;
        }
        
        if ($this->province) {
            $address[] = $this->province;
        }
        
        return implode(', ', $address);
    }

    public static function getSettings()
    {
        return self::where('is_active', true)->first() ?? new self([
            'village_name' => 'Desa Merah Putih',
            'village_code' => '320101',
            'description' => 'Koperasi Desa Merah Putih - Membangun ekonomi desa yang kuat dan berkelanjutan.',
        ]);
    }

    public static function updateSettings($data)
    {
        $settings = self::getSettings();
        
        if ($settings->exists) {
            $settings->update($data);
        } else {
            $settings = self::create(array_merge($data, ['is_active' => true]));
        }
        
        return $settings;
    }
}
