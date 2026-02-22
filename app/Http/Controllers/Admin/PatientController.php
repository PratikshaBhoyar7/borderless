<?php

namespace App\Http\Controllers\Admin;

use App\Models\Patient;
use App\Models\Country;
use App\Models\CampaignType;
use App\Imports\PatientImport;
use App\Exports\PatientTemplateExport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Excel as ExcelFormat;

class PatientController extends Controller
{

    public function index()
    {
        $patients = Patient::with(['createdBy', 'taluka', 'district', 'state', 'country', 'campaignType'])
                           ->latest()
                           ->paginate(20);
        return view('admin.patients.index', compact('patients'));
    }

    public function create()
    {
        $countries = Country::active()->get();
        $campaignTypes = CampaignType::active()->get();
        return view('admin.patients.create', compact('countries', 'campaignTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_name' => 'required|string',
            'age' => 'required|integer|min:0|max:150',
            'sex' => 'required|in:Male,Female,Other',
            'date' => 'required|date',
            'campaign_type_id' => 'nullable|exists:campaign_types,id',
            'village' => 'required|string',
            'country_id' => 'nullable|exists:countries,id',
            'state_id' => 'nullable|exists:states,id',
            'district_id' => 'nullable|exists:districts,id',
            'taluka_id' => 'nullable|exists:talukas,id',
            'mobile' => 'required|digits:10',
            'aadhar' => 'nullable|digits:12',
            'height' => 'nullable|numeric|min:0',
            'weight' => 'nullable|numeric|min:0',
            'bp' => 'nullable|string',
            'rbs' => 'nullable|integer',
            'bsl' => 'nullable|integer',
            'hb' => 'nullable|numeric',
            'complaints' => 'nullable|string',
            'known_conditions' => 'nullable|string',
            'diagnosis' => 'nullable|string',
            'treatment' => 'nullable|string',
            'dosage' => 'nullable|string',
            'lab_tests' => 'nullable|array',
            'sample_collected' => 'nullable|in:Yes,No,NA',
            'referral_type' => 'nullable|string',
            'referral_details' => 'nullable|string',
            'notes' => 'nullable|string',
            'topic_covered' => 'nullable|string',
            'bmi' => 'nullable|numeric|min:0',
            'investigation' => 'nullable|string',
            'advice' => 'nullable|string',
        ]);

        $validated['created_by'] = auth()->id();

        // Set default values based on campaign type
        if ($validated['campaign_type_id']) {
            $campaign = CampaignType::find($validated['campaign_type_id']);

            // Swatch Bharat campaign (ID: 2)
            if ($campaign && strtolower($campaign->name) === 'swatch bharat') {
                // Set default values for hidden fields
                $validated['height'] = null;
                $validated['weight'] = null;
                $validated['bp'] = null;
                $validated['rbs'] = null;
                $validated['bsl'] = null;
                $validated['hb'] = null;
                $validated['bmi'] = null;
                $validated['complaints'] = null;
                $validated['known_conditions'] = null;
                $validated['diagnosis'] = null;
                $validated['topic_covered'] = null;
                $validated['investigation'] = null;
                $validated['advice'] = null;
                $validated['treatment'] = null;
                $validated['dosage'] = null;
                $validated['lab_tests'] = null;
                $validated['sample_collected'] = null;
                $validated['referral_type'] = null;
                $validated['referral_details'] = null;
                $validated['notes'] = null;
            }

            // Special HC. Beneficiary campaign (ID: 3)
            // Visible: Patient Name, Age, Sex, Location, Mobile, Aadhar, Complaints, Investigation, Diagnosis, Treatment, Dosage, Referral Type, Referral Details, Advice
            if ($campaign && strpos(strtolower($campaign->name), 'special hc') !== false) {
                // Set default values for hidden fields
                $validated['height'] = null;
                $validated['weight'] = null;
                $validated['bp'] = null;
                $validated['rbs'] = null;
                $validated['bsl'] = null;
                $validated['hb'] = null;
                $validated['bmi'] = null;
                $validated['known_conditions'] = null;
                $validated['topic_covered'] = null;
                $validated['lab_tests'] = null;
                $validated['sample_collected'] = null;
                $validated['notes'] = null;
            }

            // Awareness camp campaign (ID: 4)
            // Visible: Patient Name, Age, Sex, Location, Mobile, Aadhar, Topic Covered, Height, Weight, BMI, Investigation, Advice
            if ($campaign && strpos(strtolower($campaign->name), 'awareness camp') !== false) {
                // Set default values for hidden fields
                $validated['complaints'] = null;
                $validated['known_conditions'] = null;
                $validated['diagnosis'] = null;
                $validated['bp'] = null;
                $validated['rbs'] = null;
                $validated['bsl'] = null;
                $validated['hb'] = null;
                $validated['treatment'] = null;
                $validated['dosage'] = null;
                $validated['lab_tests'] = null;
                $validated['sample_collected'] = null;
                $validated['referral_type'] = null;
                $validated['referral_details'] = null;
                $validated['notes'] = null;
            }
        }

        Patient::create($validated);

        return redirect()->route('admin.patients.index')
                       ->with('success', 'Patient created successfully');
    }

    public function edit(Patient $patient)
    {
        $countries = Country::active()->get();
        $campaignTypes = CampaignType::active()->get();
        return view('admin.patients.edit', compact('patient', 'countries', 'campaignTypes'));
    }

    public function update(Request $request, Patient $patient)
    {
        $validated = $request->validate([
            'patient_name' => 'required|string',
            'age' => 'required|integer|min:0|max:150',
            'sex' => 'required|in:Male,Female,Other',
            'date' => 'required|date',
            'campaign_type_id' => 'nullable|exists:campaign_types,id',
            'village' => 'required|string',
            'country_id' => 'nullable|exists:countries,id',
            'state_id' => 'nullable|exists:states,id',
            'district_id' => 'nullable|exists:districts,id',
            'taluka_id' => 'nullable|exists:talukas,id',
            'mobile' => 'required|digits:10',
            'aadhar' => 'nullable|digits:12',
            'height' => 'nullable|numeric|min:0',
            'weight' => 'nullable|numeric|min:0',
            'bp' => 'nullable|string',
            'rbs' => 'nullable|integer',
            'bsl' => 'nullable|integer',
            'hb' => 'nullable|numeric',
            'complaints' => 'nullable|string',
            'known_conditions' => 'nullable|string',
            'diagnosis' => 'nullable|string',
            'treatment' => 'nullable|string',
            'dosage' => 'nullable|string',
            'lab_tests' => 'nullable|array',
            'sample_collected' => 'nullable|in:Yes,No,NA',
            'referral_type' => 'nullable|string',
            'referral_details' => 'nullable|string',
            'notes' => 'nullable|string',
            'topic_covered' => 'nullable|string',
            'bmi' => 'nullable|numeric|min:0',
            'investigation' => 'nullable|string',
            'advice' => 'nullable|string',
        ]);

        // Set default values based on campaign type
        if ($validated['campaign_type_id']) {
            $campaign = CampaignType::find($validated['campaign_type_id']);

            // Swatch Bharat campaign (ID: 2)
            if ($campaign && strtolower($campaign->name) === 'swatch bharat') {
                // Set default values for hidden fields
                $validated['height'] = null;
                $validated['weight'] = null;
                $validated['bp'] = null;
                $validated['rbs'] = null;
                $validated['bsl'] = null;
                $validated['hb'] = null;
                $validated['bmi'] = null;
                $validated['complaints'] = null;
                $validated['known_conditions'] = null;
                $validated['diagnosis'] = null;
                $validated['topic_covered'] = null;
                $validated['investigation'] = null;
                $validated['advice'] = null;
                $validated['treatment'] = null;
                $validated['dosage'] = null;
                $validated['lab_tests'] = null;
                $validated['sample_collected'] = null;
                $validated['referral_type'] = null;
                $validated['referral_details'] = null;
                $validated['notes'] = null;
            }

            // Special HC. Beneficiary campaign (ID: 3)
            // Visible: Patient Name, Age, Sex, Location, Mobile, Aadhar, Complaints, Investigation, Diagnosis, Treatment, Dosage, Referral Type, Referral Details, Advice
            if ($campaign && strpos(strtolower($campaign->name), 'special hc') !== false) {
                // Set default values for hidden fields
                $validated['height'] = null;
                $validated['weight'] = null;
                $validated['bp'] = null;
                $validated['rbs'] = null;
                $validated['bsl'] = null;
                $validated['hb'] = null;
                $validated['bmi'] = null;
                $validated['known_conditions'] = null;
                $validated['topic_covered'] = null;
                $validated['lab_tests'] = null;
                $validated['sample_collected'] = null;
                $validated['notes'] = null;
            }

            // Awareness camp campaign (ID: 4)
            // Visible: Patient Name, Age, Sex, Location, Mobile, Aadhar, Topic Covered, Height, Weight, BMI, Investigation, Advice
            if ($campaign && strpos(strtolower($campaign->name), 'awareness camp') !== false) {
                // Set default values for hidden fields
                $validated['complaints'] = null;
                $validated['known_conditions'] = null;
                $validated['diagnosis'] = null;
                $validated['bp'] = null;
                $validated['rbs'] = null;
                $validated['bsl'] = null;
                $validated['hb'] = null;
                $validated['treatment'] = null;
                $validated['dosage'] = null;
                $validated['lab_tests'] = null;
                $validated['sample_collected'] = null;
                $validated['referral_type'] = null;
                $validated['referral_details'] = null;
                $validated['notes'] = null;
            }
        }

        $patient->update($validated);

        return redirect()->route('admin.patients.index')
                       ->with('success', 'Patient updated successfully');
    }

    public function show(Patient $patient)
    {
        $patient->load(['createdBy', 'taluka', 'district', 'state', 'country', 'campaignType']);
        return view('admin.patients.show', compact('patient'));
    }

    public function destroy(Patient $patient)
    {
        $patient->delete();

        return redirect()->route('admin.patients.index')
                       ->with('success', 'Patient deleted successfully');
    }

    public function importForm()
    {
        return view('admin.patients.import');
    }

    public function downloadTemplate()
    {
        return Excel::download(new PatientTemplateExport(), 'patient_template.xlsx');
    }

    public function import(Request $request)
    {

       
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        try {
            $import = new PatientImport();
           

            Excel::import($import, $request->file('file'));

            $successCount = $import->getSuccessCount();
            $failureCount = $import->getFailureCount();
            $duplicates = $import->getDuplicates();

            if ($successCount === 0 && $failureCount === 0) {
                return redirect()->route('admin.patients.index')
                                ->with('warning', 'No valid patient records found in the file.');
            }

            return redirect()->route('admin.patients.index')
                            ->with([
                                'success' => "Successfully imported {$successCount} patients.",
                                'failureCount' => $failureCount,
                                'duplicates' => $duplicates,
                            ]);
        } catch (\Exception $e) {
            return redirect()->route('admin.patients.index')
                            ->with('error', 'Error importing file: ' . $e->getMessage());
        }
    }

}
