<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CampaignType extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function patients()
    {
        return $this->hasMany(Patient::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
