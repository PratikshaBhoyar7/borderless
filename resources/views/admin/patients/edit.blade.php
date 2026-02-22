@extends('layouts.admin')

@section('page-title', 'Edit Patient')

@section('admin-content')

<!-- Header -->
<div style="margin-bottom: 25px;">
    <h2 style="margin: 0; color: #2e59a7;">
        <i class="bi bi-person-fill"></i> Edit Patient - {{ $patient->serial_number }}
    </h2>
</div>

<div class="table-container">
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.patients.update', $patient) }}" id="patientForm">
                @csrf
                @method('PUT')

                <!-- Serial Number Display -->
                <div class="alert alert-info mb-4">
                    <strong>Serial Number:</strong> {{ $patient->serial_number }}<br>
                    <strong>Created By:</strong> {{ $patient->createdBy->name }} on {{ $patient->created_at->format('M d, Y H:i') }}
                </div>

                <!-- Basic Information Section -->
                <h5 class="mb-3" style="color: #2e59a7; border-bottom: 2px solid #e3e6f0; padding-bottom: 10px;">
                    <i class="bi bi-person-vcard"></i> Basic Information
                </h5>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="patient_name" class="form-label">Patient Name <span style="color: red;">*</span></label>
                        <input type="text" class="form-control @error('patient_name') is-invalid @enderror" id="patient_name" name="patient_name" value="{{ old('patient_name', $patient->patient_name) }}" required>
                        @error('patient_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="date" class="form-label">Date <span style="color: red;">*</span></label>
                        <input type="date" class="form-control @error('date') is-invalid @enderror" id="date" name="date" value="{{ old('date', $patient->date->format('Y-m-d')) }}" required>
                        @error('date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="campaign_type_id" class="form-label">Campaign Type</label>
                        <select class="form-select @error('campaign_type_id') is-invalid @enderror" id="campaign_type_id" name="campaign_type_id">
                            <option value="">-- Select Campaign Type --</option>
                            @foreach ($campaignTypes as $campaign)
                                <option value="{{ $campaign->id }}" {{ old('campaign_type_id', $patient->campaign_type_id) == $campaign->id ? 'selected' : '' }}>
                                    {{ $campaign->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('campaign_type_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="age" class="form-label">Age <span style="color: red;">*</span></label>
                        <input type="number" class="form-control @error('age') is-invalid @enderror" id="age" name="age" value="{{ old('age', $patient->age) }}" min="0" max="150" required>
                        @error('age')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="sex" class="form-label">Sex <span style="color: red;">*</span></label>
                        <select class="form-select @error('sex') is-invalid @enderror" id="sex" name="sex" required>
                            <option value="">-- Select --</option>
                            <option value="Male" {{ old('sex', $patient->sex) == 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ old('sex', $patient->sex) == 'Female' ? 'selected' : '' }}>Female</option>
                            <option value="Other" {{ old('sex', $patient->sex) == 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('sex')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Location Section -->
                <h5 class="mb-3 mt-4" style="color: #2e59a7; border-bottom: 2px solid #e3e6f0; padding-bottom: 10px;">
                    <i class="bi bi-map"></i> Location Details
                </h5>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="village" class="form-label">Village <span style="color: red;">*</span></label>
                        <input type="text" class="form-control @error('village') is-invalid @enderror" id="village" name="village" value="{{ old('village', $patient->village) }}" required>
                        @error('village')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="country_id" class="form-label">Country</label>
                        <select class="form-select @error('country_id') is-invalid @enderror" id="country_id" name="country_id" onchange="loadStates()">
                            <option value="">-- Select Country --</option>
                            @foreach ($countries as $country)
                                <option value="{{ $country->id }}" {{ old('country_id', $patient->country_id) == $country->id ? 'selected' : '' }}>
                                    {{ $country->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('country_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="state_id" class="form-label">State</label>
                        <select class="form-select @error('state_id') is-invalid @enderror" id="state_id" name="state_id" onchange="loadDistricts()">
                            <option value="">-- Select State --</option>
                        </select>
                        @error('state_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="district_id" class="form-label">District</label>
                        <select class="form-select @error('district_id') is-invalid @enderror" id="district_id" name="district_id" onchange="loadTalukas()">
                            <option value="">-- Select District --</option>
                        </select>
                        @error('district_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="taluka_id" class="form-label">Taluka</label>
                        <select class="form-select @error('taluka_id') is-invalid @enderror" id="taluka_id" name="taluka_id">
                            <option value="">-- Select Taluka --</option>
                        </select>
                        @error('taluka_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="mobile" class="form-label">Mobile Number <span style="color: red;">*</span></label>
                        <input type="text" class="form-control @error('mobile') is-invalid @enderror" id="mobile" name="mobile" value="{{ old('mobile', $patient->mobile) }}" pattern="[0-9]{10}" placeholder="10-digit number" required>
                        @error('mobile')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="aadhar" class="form-label">Aadhar Number</label>
                        <input type="text" class="form-control @error('aadhar') is-invalid @enderror" id="aadhar" name="aadhar" value="{{ old('aadhar', $patient->aadhar) }}" pattern="[0-9]{12}" placeholder="12-digit number">
                        @error('aadhar')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Vital Signs Section -->
                <div id="vital_signs_section">
                    <h5 class="mb-3 mt-4" style="color: #2e59a7; border-bottom: 2px solid #e3e6f0; padding-bottom: 10px;">
                        <i class="bi bi-heart-pulse"></i> Vital Signs
                    </h5>

                    <div class="row">
                        <div class="col-md-3 mb-3" id="height_field">
                            <label for="height" class="form-label">Height (cm)</label>
                        <input type="number" step="0.1" class="form-control @error('height') is-invalid @enderror" id="height" name="height" value="{{ old('height', $patient->height) }}">
                        @error('height')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3 mb-3" id="weight_field">
                        <label for="weight" class="form-label">Weight (kg)</label>
                        <input type="number" step="0.1" class="form-control @error('weight') is-invalid @enderror" id="weight" name="weight" value="{{ old('weight', $patient->weight) }}">
                        @error('weight')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3 mb-3" id="bp_field">
                        <label for="bp" class="form-label">Blood Pressure</label>
                        <input type="text" class="form-control @error('bp') is-invalid @enderror" id="bp" name="bp" value="{{ old('bp', $patient->bp) }}" placeholder="e.g., 120/80">
                        @error('bp')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3 mb-3" id="hb_field">
                        <label for="hb" class="form-label">Hemoglobin (g/dL)</label>
                        <input type="number" step="0.1" class="form-control @error('hb') is-invalid @enderror" id="hb" name="hb" value="{{ old('hb', $patient->hb) }}">
                        @error('hb')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                    <div class="row">
                        <div class="col-md-6 mb-3" id="rbs_field">
                            <label for="rbs" class="form-label">RBS (mg/dL)</label>
                            <input type="number" class="form-control @error('rbs') is-invalid @enderror" id="rbs" name="rbs" value="{{ old('rbs', $patient->rbs) }}">
                            @error('rbs')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3" id="bsl_field">
                            <label for="bsl" class="form-label">BSL (mg/dL)</label>
                            <input type="number" class="form-control @error('bsl') is-invalid @enderror" id="bsl" name="bsl" value="{{ old('bsl', $patient->bsl) }}">
                            @error('bsl')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- BMI Field (for Awareness Camp) -->
                    <div class="row" id="bmi_field">
                        <div class="col-md-6 mb-3">
                            <label for="bmi" class="form-label">BMI</label>
                            <input type="number" step="0.01" class="form-control @error('bmi') is-invalid @enderror" id="bmi" name="bmi" value="{{ old('bmi', $patient->bmi) }}">
                            @error('bmi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Clinical Information Section -->
                <div id="clinical_section">
                    <h5 class="mb-3 mt-4" style="color: #2e59a7; border-bottom: 2px solid #e3e6f0; padding-bottom: 10px;">
                        <i class="bi bi-stethoscope"></i> Clinical Information
                    </h5>

                    <div class="mb-3" id="complaints_field">
                        <label for="complaints" class="form-label">Chief Complaints <span style="color: red;">*</span></label>
                        <textarea class="form-control @error('complaints') is-invalid @enderror" id="complaints" name="complaints" rows="3">{{ old('complaints', $patient->complaints) }}</textarea>
                        @error('complaints')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3" id="known_conditions_field">
                        <label for="known_conditions" class="form-label">Known Conditions (K/O/C)</label>
                        <textarea class="form-control @error('known_conditions') is-invalid @enderror" id="known_conditions" name="known_conditions" rows="3">{{ old('known_conditions', $patient->known_conditions) }}</textarea>
                        @error('known_conditions')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3" id="diagnosis_field">
                        <label for="diagnosis" class="form-label">Diagnosis <span style="color: red;">*</span></label>
                        <textarea class="form-control @error('diagnosis') is-invalid @enderror" id="diagnosis" name="diagnosis" rows="3">{{ old('diagnosis', $patient->diagnosis) }}</textarea>
                        @error('diagnosis')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Topic Covered (for Awareness Camp) -->
                    <div class="mb-3" id="topic_covered_field">
                        <label for="topic_covered" class="form-label">Topic Covered</label>
                        <input type="text" class="form-control @error('topic_covered') is-invalid @enderror" id="topic_covered" name="topic_covered" value="{{ old('topic_covered', $patient->topic_covered) }}">
                        @error('topic_covered')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Investigation Field (for Awareness Camp and Special HC) -->
                    <div class="mb-3" id="investigation_field">
                        <label for="investigation" class="form-label">Other Investigations/Examinations/Screening (BP/BSL/HB/ECG/etc.)</label>
                        <textarea class="form-control @error('investigation') is-invalid @enderror" id="investigation" name="investigation" rows="3">{{ old('investigation', $patient->investigation) }}</textarea>
                        @error('investigation')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Advice Field (for Awareness Camp and Special HC) -->
                    <div class="mb-3" id="advice_field">
                        <label for="advice" class="form-label">Advice/Referral If any</label>
                        <textarea class="form-control @error('advice') is-invalid @enderror" id="advice" name="advice" rows="3">{{ old('advice', $patient->advice) }}</textarea>
                        @error('advice')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Treatment Section -->
                <div id="treatment_section">
                    <h5 class="mb-3 mt-4" style="color: #2e59a7; border-bottom: 2px solid #e3e6f0; padding-bottom: 10px;">
                        <i class="bi bi-pill"></i> Treatment
                    </h5>

                    <div class="mb-3" id="treatment_field">
                        <label for="treatment" class="form-label">Treatment <span style="color: red;">*</span></label>
                        <textarea class="form-control @error('treatment') is-invalid @enderror" id="treatment" name="treatment" rows="3">{{ old('treatment', $patient->treatment) }}</textarea>
                        @error('treatment')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3" id="dosage_field">
                        <label for="dosage" class="form-label">Dosage <span style="color: red;">*</span></label>
                        <textarea class="form-control @error('dosage') is-invalid @enderror" id="dosage" name="dosage" rows="3">{{ old('dosage', $patient->dosage) }}</textarea>
                        @error('dosage')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Lab & Referral Section -->
                <div id="lab_referral_section">
                    <h5 class="mb-3 mt-4" style="color: #2e59a7; border-bottom: 2px solid #e3e6f0; padding-bottom: 10px;">
                        <i class="bi bi-flask"></i> Lab Tests & Referral
                    </h5>

                    <div class="mb-3" id="lab_tests_field">
                    <label class="form-label">Lab Tests</label>
                    <div class="form-group">
                        @php
                            $labTests = old('lab_tests', $patient->lab_tests ?? []);
                        @endphp
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="lab_tests[]" value="Blood Test" id="labBlood" {{ in_array('Blood Test', $labTests) ? 'checked' : '' }}>
                            <label class="form-check-label" for="labBlood">Blood Test</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="lab_tests[]" value="Urine Test" id="labUrine" {{ in_array('Urine Test', $labTests) ? 'checked' : '' }}>
                            <label class="form-check-label" for="labUrine">Urine Test</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="lab_tests[]" value="X-Ray" id="labXray" {{ in_array('X-Ray', $labTests) ? 'checked' : '' }}>
                            <label class="form-check-label" for="labXray">X-Ray</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="lab_tests[]" value="ECG" id="labECG" {{ in_array('ECG', $labTests) ? 'checked' : '' }}>
                            <label class="form-check-label" for="labECG">ECG</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="lab_tests[]" value="Ultrasound" id="labUSG" {{ in_array('Ultrasound', $labTests) ? 'checked' : '' }}>
                            <label class="form-check-label" for="labUSG">Ultrasound</label>
                        </div>
                        </div>
                    </div>

                    <div class="row" id="sample_collected_field">
                        <div class="col-md-6 mb-3">
                            <label for="sample_collected" class="form-label">Sample Collected</label>
                            <select class="form-select @error('sample_collected') is-invalid @enderror" id="sample_collected" name="sample_collected">
                                <option value="">-- Select --</option>
                                <option value="Yes" {{ old('sample_collected', $patient->sample_collected) == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ old('sample_collected', $patient->sample_collected) == 'No' ? 'selected' : '' }}>No</option>
                                <option value="NA" {{ old('sample_collected', $patient->sample_collected) == 'NA' ? 'selected' : '' }}>N/A</option>
                            </select>
                            @error('sample_collected')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row" id="referral_fields">
                        <div class="col-md-6 mb-3" id="referral_type_field">
                            <label for="referral_type" class="form-label">Referral Type</label>
                            <input type="text" class="form-control @error('referral_type') is-invalid @enderror" id="referral_type" name="referral_type" value="{{ old('referral_type', $patient->referral_type) }}" placeholder="e.g., Specialist, Hospital">
                            @error('referral_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3" id="referral_details_field">
                        <label for="referral_details" class="form-label">Referral Details</label>
                        <textarea class="form-control @error('referral_details') is-invalid @enderror" id="referral_details" name="referral_details" rows="2">{{ old('referral_details', $patient->referral_details) }}</textarea>
                        @error('referral_details')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Additional Notes -->
                <div id="notes_section">
                    <h5 class="mb-3 mt-4" style="color: #2e59a7; border-bottom: 2px solid #e3e6f0; padding-bottom: 10px;">
                        <i class="bi bi-sticky"></i> Additional Notes
                    </h5>

                    <div class="mb-3" id="notes_field">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3">{{ old('notes', $patient->notes) }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg"></i> Update Patient
                    </button>
                    <a href="{{ route('admin.patients.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Campaign ID and name mapping
const SWATCH_BHARAT_ID = 2;
const SWATCH_BHARAT_NAME = 'Swatch bharat';
const SPECIAL_HC_BENEFICIARY_ID = 3;
const SPECIAL_HC_BENEFICIARY_NAME = 'Special HC. Beneficiary';
const AWARENESS_CAMP_ID = 4;
const AWARENESS_CAMP_NAME = 'Awareness camp';

// Fields to hide for Swatch Bharat campaign
const hiddenFieldsForSwatchBharat = [
    'vital_signs_section',      // Hide entire Vital Signs section
    'clinical_section',         // Hide entire Clinical Information section
    'treatment_section',        // Hide entire Treatment section
    'lab_referral_section',     // Hide entire Lab & Referral section
    'notes_section',            // Hide entire Additional Notes section
];

// Fields to hide for Special HC. Beneficiary campaign
// Visible fields: Patient Name, Age, Sex, Country, State, District, Village, Mobile, Aadhar, Chief Complaints, Investigation, Diagnosis, Treatment, Dosage, Referral Type, Referral Details, Advice
const hiddenFieldsForSpecialHC = [
    'height_field',             // Hide Height
    'weight_field',             // Hide Weight
    'bp_field',                 // Hide BP
    'hb_field',                 // Hide HB
    'rbs_field',                // Hide RBS
    'bsl_field',                // Hide BSL
    'bmi_field',                // Hide BMI
    'known_conditions_field',   // Hide Known Conditions
    'topic_covered_field',      // Hide Topic Covered
    'lab_tests_field',          // Hide Lab Tests checkboxes
    'sample_collected_field',   // Hide Sample Collected
    'notes_field',              // Hide Notes
];

// Fields to hide for Awareness camp campaign
// Visible fields: Patient Name, Age, Sex, Country, State, District, Village, Mobile, Aadhar, Topic Covered, Height, Weight, BMI, Investigation, Advice
const hiddenFieldsForAwarenesscamp = [
    'complaints_field',         // Hide Chief Complaints
    'known_conditions_field',   // Hide Known Conditions
    'diagnosis_field',          // Hide Diagnosis
    'bp_field',                 // Hide BP
    'hb_field',                 // Hide HB
    'rbs_field',                // Hide RBS
    'bsl_field',                // Hide BSL
    'treatment_field',          // Hide Treatment
    'dosage_field',             // Hide Dosage
    'lab_tests_field',          // Hide Lab Tests
    'sample_collected_field',   // Hide Sample Collected
    'referral_type_field',      // Hide Referral Type
    'referral_details_field',   // Hide Referral Details
    'notes_field',              // Hide Notes
];

function toggleSwatchBharatFields() {
    const campaignSelect = document.getElementById('campaign_type_id');
    const selectedId = campaignSelect.value;
    const selectedText = campaignSelect.options[campaignSelect.selectedIndex].text;

    // Check if it's Swatch Bharat campaign (ID 2 and name contains "Swatch bharat")
    const isSwatchBharat = selectedId == SWATCH_BHARAT_ID && selectedText.toLowerCase().includes('swatch bharat');

    // Toggle visibility of sections
    hiddenFieldsForSwatchBharat.forEach(fieldId => {
        const element = document.getElementById(fieldId);
        if (element) {
            element.style.display = isSwatchBharat ? 'none' : 'block';
        }
    });

    // Toggle required attribute on visible fields
    const vitalSignsInputs = document.querySelectorAll('#vital_signs_section input, #vital_signs_section textarea');
    const clinicalInputs = document.querySelectorAll('#clinical_section input, #clinical_section textarea, #clinical_section select');
    const treatmentInputs = document.querySelectorAll('#treatment_section input, #treatment_section textarea');
    const labInputs = document.querySelectorAll('#lab_referral_section input, #lab_referral_section textarea, #lab_referral_section select, #lab_referral_section .form-check-input');
    const notesInputs = document.querySelectorAll('#notes_section textarea');

    [vitalSignsInputs, clinicalInputs, treatmentInputs, labInputs, notesInputs].forEach(inputs => {
        inputs.forEach(input => {
            if (isSwatchBharat && input.hasAttribute('required')) {
                input.dataset.wasRequired = 'true';
                input.removeAttribute('required');
            } else if (!isSwatchBharat && input.dataset.wasRequired === 'true') {
                input.setAttribute('required', 'required');
            }
        });
    });
}

function toggleSpecialHCFields() {
    const campaignSelect = document.getElementById('campaign_type_id');
    const selectedId = campaignSelect.value;
    const selectedText = campaignSelect.options[campaignSelect.selectedIndex].text;

    // Check if it's Special HC. Beneficiary campaign (ID 3 and name contains "Special HC. Beneficiary")
    const isSpecialHC = selectedId == SPECIAL_HC_BENEFICIARY_ID && selectedText.toLowerCase().includes('special hc');

    // Toggle visibility of individual fields
    hiddenFieldsForSpecialHC.forEach(fieldId => {
        const element = document.getElementById(fieldId);
        if (element) {
            element.style.display = isSpecialHC ? 'none' : 'block';
        }
    });

    // Toggle required attribute on visible fields
    const heightInput = document.getElementById('height');
    const weightInput = document.getElementById('weight');
    const bpInput = document.getElementById('bp');
    const hbInput = document.getElementById('hb');
    const rbsInput = document.getElementById('rbs');
    const bslInput = document.getElementById('bsl');
    const bmiInput = document.getElementById('bmi');
    const knownConditionsInput = document.getElementById('known_conditions');
    const topicCoveredInput = document.getElementById('topic_covered');
    const notesInput = document.getElementById('notes');
    const labCheckboxes = document.querySelectorAll('#lab_tests_field .form-check-input');
    const sampleCollectedSelect = document.getElementById('sample_collected');

    const fieldsToToggle = [heightInput, weightInput, bpInput, hbInput, rbsInput, bslInput, bmiInput, knownConditionsInput, topicCoveredInput, notesInput, sampleCollectedSelect];

    fieldsToToggle.forEach(field => {
        if (field) {
            if (isSpecialHC && field.hasAttribute('required')) {
                field.dataset.wasRequired = 'true';
                field.removeAttribute('required');
            } else if (!isSpecialHC && field.dataset.wasRequired === 'true') {
                field.setAttribute('required', 'required');
            }
        }
    });

    labCheckboxes.forEach(checkbox => {
        if (isSpecialHC && checkbox.hasAttribute('required')) {
            checkbox.dataset.wasRequired = 'true';
            checkbox.removeAttribute('required');
        } else if (!isSpecialHC && checkbox.dataset.wasRequired === 'true') {
            checkbox.setAttribute('required', 'required');
        }
    });
}

function toggleAwarenesscamp() {
    const campaignSelect = document.getElementById('campaign_type_id');
    const selectedId = campaignSelect.value;
    const selectedText = campaignSelect.options[campaignSelect.selectedIndex].text;

    // Check if it's Awareness camp campaign (ID 4 and name contains "Awareness camp")
    const isAwarenesscamp = selectedId == AWARENESS_CAMP_ID && selectedText.toLowerCase().includes('awareness camp');

    // Toggle visibility of individual fields
    hiddenFieldsForAwarenesscamp.forEach(fieldId => {
        const element = document.getElementById(fieldId);
        if (element) {
            element.style.display = isAwarenesscamp ? 'none' : 'block';
        }
    });

    // Toggle required attribute on visible fields
    const complaintsInput = document.getElementById('complaints');
    const knownConditionsInput = document.getElementById('known_conditions');
    const diagnosisInput = document.getElementById('diagnosis');
    const bpInput = document.getElementById('bp');
    const hbInput = document.getElementById('hb');
    const rbsInput = document.getElementById('rbs');
    const bslInput = document.getElementById('bsl');
    const treatmentInput = document.getElementById('treatment');
    const dosageInput = document.getElementById('dosage');
    const labCheckboxes = document.querySelectorAll('#lab_tests_field .form-check-input');
    const sampleCollectedSelect = document.getElementById('sample_collected');
    const referralTypeInput = document.getElementById('referral_type');
    const referralDetailsInput = document.getElementById('referral_details');
    const notesInput = document.getElementById('notes');

    const fieldsToToggle = [complaintsInput, knownConditionsInput, diagnosisInput, bpInput, hbInput, rbsInput, bslInput, treatmentInput, dosageInput, sampleCollectedSelect, referralTypeInput, referralDetailsInput, notesInput];

    fieldsToToggle.forEach(field => {
        if (field) {
            if (isAwarenesscamp && field.hasAttribute('required')) {
                field.dataset.wasRequired = 'true';
                field.removeAttribute('required');
            } else if (!isAwarenesscamp && field.dataset.wasRequired === 'true') {
                field.setAttribute('required', 'required');
            }
        }
    });

    labCheckboxes.forEach(checkbox => {
        if (isAwarenesscamp && checkbox.hasAttribute('required')) {
            checkbox.dataset.wasRequired = 'true';
            checkbox.removeAttribute('required');
        } else if (!isAwarenesscamp && checkbox.dataset.wasRequired === 'true') {
            checkbox.setAttribute('required', 'required');
        }
    });
}

function loadStates() {
    const countryId = document.getElementById('country_id').value;
    const stateSelect = document.getElementById('state_id');

    stateSelect.innerHTML = '<option value="">-- Select State --</option>';
    document.getElementById('district_id').innerHTML = '<option value="">-- Select District --</option>';
    document.getElementById('taluka_id').innerHTML = '<option value="">-- Select Taluka --</option>';

    if (!countryId) return;

    fetch(`/admin/states/by-country/${countryId}`)
        .then(response => response.json())
        .then(data => {
            data.forEach(state => {
                const option = document.createElement('option');
                option.value = state.id;
                option.textContent = state.name;
                stateSelect.appendChild(option);
            });
        })
        .catch(error => console.error('Error:', error));
}

function loadDistricts() {
    const stateId = document.getElementById('state_id').value;
    const districtSelect = document.getElementById('district_id');

    districtSelect.innerHTML = '<option value="">-- Select District --</option>';
    document.getElementById('taluka_id').innerHTML = '<option value="">-- Select Taluka --</option>';

    if (!stateId) return;

    fetch(`/admin/districts/by-state/${stateId}`)
        .then(response => response.json())
        .then(data => {
            data.forEach(district => {
                const option = document.createElement('option');
                option.value = district.id;
                option.textContent = district.name;
                districtSelect.appendChild(option);
            });
        })
        .catch(error => console.error('Error:', error));
}

function loadTalukas() {
    const districtId = document.getElementById('district_id').value;
    const talukaSelect = document.getElementById('taluka_id');

    talukaSelect.innerHTML = '<option value="">-- Select Taluka --</option>';

    if (!districtId) return;

    fetch(`/admin/talukas/by-district/${districtId}`)
        .then(response => response.json())
        .then(data => {
            data.forEach(taluka => {
                const option = document.createElement('option');
                option.value = taluka.id;
                option.textContent = taluka.name;
                talukaSelect.appendChild(option);
            });
        })
        .catch(error => console.error('Error:', error));
}

// Load initial cascading dropdowns on page load if values exist
document.addEventListener('DOMContentLoaded', function() {
    // Initialize campaign field visibility
    toggleSwatchBharatFields();
    toggleSpecialHCFields();
    toggleAwarenesscamp();

    // Add event listener to campaign type dropdown
    const campaignSelect = document.getElementById('campaign_type_id');
    campaignSelect.addEventListener('change', function() {
        toggleSwatchBharatFields();
        toggleSpecialHCFields();
        toggleAwarenesscamp();
    });

    @if($patient->country_id)
        loadStates();
        setTimeout(() => {
            @if($patient->state_id)
                document.getElementById('state_id').value = '{{ $patient->state_id }}';
                loadDistricts();
                setTimeout(() => {
                    @if($patient->district_id)
                        document.getElementById('district_id').value = '{{ $patient->district_id }}';
                        loadTalukas();
                        @if($patient->taluka_id)
                            setTimeout(() => {
                                document.getElementById('taluka_id').value = '{{ $patient->taluka_id }}';
                            }, 100);
                        @endif
                    @endif
                }, 100);
            @endif
        }, 100);
    @endif
});
</script>

@endsection
