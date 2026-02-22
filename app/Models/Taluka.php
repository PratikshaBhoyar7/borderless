<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Taluka extends Model
{
    protected $fillable = [
        'district_id',
        'name',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function district()
    {
        return $this->belongsTo(District::class);
    }
    public function villages()
    {
        return $this->hasMany(Village::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
