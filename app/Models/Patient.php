<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Patient extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'serial_number',
        'created_by',
        'patient_name',
        'age',
        'sex',
        'date',
        'campaign_type_id',
        'village',
        'taluka_id',
        'district_id',
        'state_id',
        'country_id',
        'mobile',
        'aadhar',
        'height',
        'weight',
        'bp',
        'rbs',
        'bsl',
        'hb',
        'complaints',
        'known_conditions',
        'diagnosis',
        'treatment',
        'dosage',
        'lab_tests',
        'sample_collected',
        'referral_type',
        'referral_details',
        'notes',
        'topic_covered',
        'bmi',
        'investigation',
        'advice',
    ];

    protected $casts = [
        'date' => 'date',
        'lab_tests' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($patient) {
            if (!$patient->serial_number) {
                $year = date('Y');
                $lastPatient = Patient::whereYear('created_at', $year)
                                     ->orderBy('id', 'desc')
                                     ->first();

                $number = $lastPatient ?
                          (int) substr($lastPatient->serial_number, -4) + 1 :
                          1;

                $patient->serial_number = 'PAT-' . $year . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function taluka()
    {
        return $this->belongsTo(Taluka::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function campaignType()
    {
        return $this->belongsTo(CampaignType::class);
    }
}
