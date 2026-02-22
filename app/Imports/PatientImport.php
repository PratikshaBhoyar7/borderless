<?php

namespace App\Imports;

use App\Models\Patient;
use App\Models\Taluka;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class PatientImport implements ToCollection, WithHeadingRow
{
    protected $duplicates = [];
    protected $successCount = 0;
    protected $failureCount = 0;
    protected $keywordMap = [
        'sex' => 'sex',
        'date' => 'date',
        'mobile' => 'mobile',
        'sample_collected' => 'sample_collected',
        'aadhar' => 'aadhar',
        'rbs' => 'rbs',
        'bsl' => 'bsl',
        'sugar' => 'rbs',
        'hb' => 'hb',
        'hemoglobin' => 'hb',
        'lab_tests' => 'lab_tests',
        'tests' => 'lab_tests',
        'patient_name' => 'patient_name',
        'age' => 'age',
        'village' => 'village',
        'taluka_id' => 'taluka_id',
        'district_reference' => 'district_reference', 
        'complaints' => 'complaints',
        'diagnosis' => 'diagnosis',
        'treatment' => 'treatment',
        'dosage' => 'dosage',
        'weight' => 'weight',
        'height' => 'height',
        'bp' => 'bp',
        'referral_type' => 'referral_type',
        'notes' => 'notes',
    ];

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            try {
                // 1. Dynamic Map the row keys
                $mappedRow = $this->mapDynamicRow($row);

                // Skip empty rows
                if (empty($mappedRow['patient_name'])) {
                    continue;
                }

                // 2. Validate the mapped data
                $validator = Validator::make($mappedRow, $this->rules());

                if ($validator->fails()) {
                    $this->duplicates[] = [
                        'patient_name' => $mappedRow['patient_name'] ?? 'Unknown',
                        'date' => $mappedRow['date'] ?? 'Unknown',
                        'aadhar' => $mappedRow['aadhar'] ?? 'N/A',
                        'reason' => implode(', ', $validator->errors()->all())
                    ];
                    $this->failureCount++;
                    continue;
                }

                // Parse the date
                $date = $this->parseDate($mappedRow['date']);
                if (!$date) {
                    throw new \Exception('Invalid date format');
                }

                // Check for duplicates
                if ($this->isDuplicate($mappedRow['patient_name'], $date, $mappedRow['aadhar'] ?? null)) {
                    $this->duplicates[] = [
                        'patient_name' => $mappedRow['patient_name'],
                        'date' => $mappedRow['date'],
                        'aadhar' => $mappedRow['aadhar'] ?? 'N/A',
                        'reason' => 'Patient with same name, date, and aadhar already exists'
                    ];
                    $this->failureCount++;
                    continue;
                }

                // Create the patient
                $this->createPatient($mappedRow, $date);
                $this->successCount++;
            } catch (\Exception $e) {
                $this->duplicates[] = [
                    'patient_name' => $row['patient_name'] ?? 'Unknown', // Use raw row for fallback
                    'date' => $row['date'] ?? 'Unknown',
                    'aadhar' => $row['aadhar'] ?? 'N/A',
                    'reason' => $e->getMessage()
                ];
                $this->failureCount++;
            }
        }
    }

    protected function mapDynamicRow($row)
    {
        $mapped = [];
        $rowArray = $row instanceof Collection ? $row->toArray() : $row;
        foreach ($rowArray as $key => $value) {
            $foundMapping = false;
            foreach ($this->keywordMap as $keyword => $attribute) {
                if (str_starts_with(strtolower($key), $keyword)) {
                    if (!isset($mapped[$attribute])) {
                        $mapped[$attribute] = $value;
                        $foundMapping = true;
                        break;
                    }
                }
            }
            if (!$foundMapping) {
                $mapped[$key] = $value;
            }
        }
        return $mapped;
    }

    public function rules(): array
    {
        return [
            'patient_name' => 'required|string|max:255',
            'age' => 'required|integer|min:0|max:150',
            'sex' => 'required|in:Male,Female,Other',
            'date' => 'required',
            'village' => 'required|string|max:255',
            'taluka_id' => 'required|integer|exists:talukas,id',
            'mobile' => 'required|regex:/^[0-9]{10}$/',
            'aadhar' => 'nullable|regex:/^[0-9]{12}$/',
            'height' => 'nullable|numeric|min:0|max:999.99',
            'weight' => 'nullable|numeric|min:0|max:999.99',
            'bp' => 'nullable|string|max:20',
            'rbs' => 'nullable|integer|min:0|max:600',
            'bsl' => 'nullable|integer|min:0|max:600',
            'hb' => 'nullable|numeric|min:0|max:20',
            'complaints' => 'required|string',
            'known_conditions' => 'nullable|string',
            'diagnosis' => 'required|string',
            'treatment' => 'required|string',
            'dosage' => 'required|string',
            'lab_tests' => 'nullable|string',
            'sample_collected' => 'nullable|in:Yes,No,NA',
            'referral_type' => 'nullable|string|max:255',
            'referral_details' => 'nullable|string',
            'notes' => 'nullable|string',
        ];
    }

    protected function parseDate($dateString)
    {
        try {
            // Try DD/MM/YYYY format first
            if (preg_match('/^\d{1,2}\/\d{1,2}\/\d{4}$/', $dateString)) {
                return Carbon::createFromFormat('d/m/Y', $dateString);
            }

            // Try other common formats
            return Carbon::parse($dateString);
        } catch (\Exception $e) {
            return null;
        }
    }

    protected function isDuplicate($patientName, $date, $aadhar = null)
    {
        $query = Patient::where('patient_name', $patientName)
            ->whereDate('date', $date);
        if (!empty($aadhar)) {
            $query->where('aadhar', $aadhar);
        }
        return $query->exists();
    }

    protected function createPatient($row, $date)
    {
        // Get taluka to fetch location hierarchy
        $taluka = Taluka::with(['district.state.country'])->find($row['taluka_id']);
        if (!$taluka) {
            throw new \Exception('Invalid Taluka ID');
        }

        // Parse lab tests if provided
        $labTests = [];
        if (!empty($row['lab_tests'])) {
            $labTests = array_filter(array_map('trim', explode(',', $row['lab_tests'])));
        }

        $patientData = [
            'patient_name' => $row['patient_name'],
            'age' => $row['age'],
            'sex' => $row['sex'],
            'date' => $date,
            'village' => $row['village'],
            'taluka_id' => $row['taluka_id'],
            'district_id' => $taluka->district_id,
            'state_id' => $taluka->district->state_id,
            'country_id' => $taluka->district->state->country_id,
            'mobile' => $row['mobile'],
            'aadhar' => $row['aadhar'] ?? null,
            'height' => $row['height'] ?? null,
            'weight' => $row['weight'] ?? null,
            'bp' => $row['bp'] ?? null,
            'rbs' => $row['rbs'] ?? null,
            'bsl' => $row['bsl'] ?? null,
            'hb' => $row['hb'] ?? null,
            'complaints' => $row['complaints'],
            'known_conditions' => $row['known_conditions'] ?? null,
            'diagnosis' => $row['diagnosis'],
            'treatment' => $row['treatment'],
            'dosage' => $row['dosage'],
            'lab_tests' => !empty($labTests) ? json_encode($labTests) : null,
            'sample_collected' => isset($row['sample_collected']) ? $row['sample_collected'] : null,
            'referral_type' => $row['referral_type'] ?? null,
            'referral_details' => $row['referral_details'] ?? null,
            'notes' => $row['notes'] ?? null,
            'created_by' => auth()->id(),
        ];

        Patient::create($patientData);
    }

    public function getSuccessCount()
    {
        return $this->successCount;
    }

    public function getFailureCount()
    {
        return $this->failureCount;
    }

    public function getDuplicates()
    {
        return $this->duplicates;
    }
}
