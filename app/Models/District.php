<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    protected $fillable = [
        'state_id',
        'name',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function talukas()
    {
        return $this->hasMany(Taluka::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
