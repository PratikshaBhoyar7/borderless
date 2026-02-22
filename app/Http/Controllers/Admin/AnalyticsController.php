<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Country;
use App\Models\State;
use App\Models\District;
use App\Models\Taluka;
use App\Models\CampaignType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    /**
     * Display the analytics dashboard
     */
    public function index()
    {
        $countries = Country::active()->orderBy('name')->get(['id', 'name']);
        $campaignTypes = CampaignType::active()->orderBy('name')->get(['id', 'name']);

        return view('admin.analytics.index', compact('countries', 'campaignTypes'));
    }

    /**
     * Get patient statistics (KPIs)
     */
    public function getPatientStats(Request $request)
    {
        $query = Patient::query();
        $this->applyFilters($query, $request);

        $stats = [
            'total_patients' => $query->count(),
            'male_patients' => (clone $query)->where('sex', 'Male')->count(),
            'female_patients' => (clone $query)->where('sex', 'Female')->count(),
            'avg_age' => round((clone $query)->avg('age'), 1),
            'samples_collected' => (clone $query)->where('sample_collected', 'Yes')->count(),
            'referrals_made' => (clone $query)->whereNotNull('referral_type')->count(),
        ];

        return response()->json($stats);
    }

    /**
     * Get patient registration trend over time
     */
    public function getRegistrationTrend(Request $request)
    {
        $query = Patient::query();
        $this->applyFilters($query, $request);

        $period = $request->input('period', 'month');

        switch ($period) {
            case 'day':
                $groupFormat = '%Y-%m-%d';
                $days = 30;
                break;
            case 'week':
                $groupFormat = '%Y-%u';
                $days = 84;
                break;
            case 'year':
                $groupFormat = '%Y';
                $days = 1095;
                break;
            default:
                $groupFormat = '%Y-%m';
                $days = 365;
                break;
        }

        $data = $query
            ->select(
                DB::raw("DATE_FORMAT(date, '{$groupFormat}') as period"),
                DB::raw('COUNT(*) as count')
            )
            ->where('date', '>=', Carbon::now()->subDays($days))
            ->groupBy('period')
            ->orderBy('period')
            ->get();

        return response()->json($data);
    }

    /**
     * Get demographic distributions (gender, age groups)
     */
    public function getDemographics(Request $request)
    {
        $query = Patient::query();
        $this->applyFilters($query, $request);

        // Gender distribution
        $genderData = (clone $query)
            ->select('sex', DB::raw('COUNT(*) as count'))
            ->groupBy('sex')
            ->get();

        // Age group distribution
        $ageGroups = (clone $query)
            ->select(
                DB::raw('CASE
                    WHEN age < 18 THEN "0-17"
                    WHEN age BETWEEN 18 AND 30 THEN "18-30"
                    WHEN age BETWEEN 31 AND 45 THEN "31-45"
                    WHEN age BETWEEN 46 AND 60 THEN "46-60"
                    ELSE "60+"
                END as age_group'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('age_group')
            ->orderByRaw('FIELD(age_group, "0-17", "18-30", "31-45", "46-60", "60+")')
            ->get();

        // Top villages
        $topVillages = (clone $query)
            ->select('village', DB::raw('COUNT(*) as count'))
            ->groupBy('village')
            ->orderByDesc('count')
            ->limit(10)
            ->get();

        // Age distribution by gender
        $ageByGender = (clone $query)
            ->select(
                'sex',
                DB::raw('CASE
                    WHEN age < 18 THEN "0-17"
                    WHEN age BETWEEN 18 AND 30 THEN "18-30"
                    WHEN age BETWEEN 31 AND 45 THEN "31-45"
                    WHEN age BETWEEN 46 AND 60 THEN "46-60"
                    ELSE "60+"
                END as age_group'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('sex', 'age_group')
            ->get();

        return response()->json([
            'gender' => $genderData,
            'age_groups' => $ageGroups,
            'top_villages' => $topVillages,
            'age_by_gender' => $ageByGender,
        ]);
    }

    /**
     * Get health metrics analysis (BP, RBS, BMI)
     */
    public function getHealthMetrics(Request $request)
    {
        $query = Patient::query();
        $this->applyFilters($query, $request);

        // BP Status Analysis
        $bpStatus = (clone $query)
            ->whereNotNull('bp')
            ->select(
                DB::raw('CASE
                    WHEN bp REGEXP "^[0-9]+/[0-9]+$" THEN
                        CASE
                            WHEN CAST(SUBSTRING_INDEX(bp, "/", 1) AS UNSIGNED) < 120
                                AND CAST(SUBSTRING_INDEX(bp, "/", -1) AS UNSIGNED) < 80
                                THEN "Normal"
                            WHEN CAST(SUBSTRING_INDEX(bp, "/", 1) AS UNSIGNED) BETWEEN 120 AND 139
                                OR CAST(SUBSTRING_INDEX(bp, "/", -1) AS UNSIGNED) BETWEEN 80 AND 89
                                THEN "Prehypertension"
                            WHEN CAST(SUBSTRING_INDEX(bp, "/", 1) AS UNSIGNED) >= 140
                                OR CAST(SUBSTRING_INDEX(bp, "/", -1) AS UNSIGNED) >= 90
                                THEN "Hypertension"
                            ELSE "Invalid"
                        END
                    ELSE "Invalid"
                END as status'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('status')
            ->get();

        // RBS Levels Analysis
        $rbsLevels = (clone $query)
            ->whereNotNull('rbs')
            ->select(
                DB::raw('CASE
                    WHEN rbs < 140 THEN "Normal (<140)"
                    WHEN rbs BETWEEN 140 AND 199 THEN "Prediabetic (140-199)"
                    ELSE "Diabetic (≥200)"
                END as level'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('level')
            ->orderByRaw('FIELD(level, "Normal (<140)", "Prediabetic (140-199)", "Diabetic (≥200)")')
            ->get();

        // BMI Analysis (calculated from height and weight)
        $bmiAnalysis = (clone $query)
            ->whereNotNull('height')
            ->whereNotNull('weight')
            ->where('height', '>', 0)
            ->where('weight', '>', 0)
            ->select(
                DB::raw('CASE
                    WHEN (weight / ((height/100) * (height/100))) < 18.5 THEN "Underweight"
                    WHEN (weight / ((height/100) * (height/100))) BETWEEN 18.5 AND 24.9 THEN "Normal"
                    WHEN (weight / ((height/100) * (height/100))) BETWEEN 25 AND 29.9 THEN "Overweight"
                    ELSE "Obese"
                END as category'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('category')
            ->orderByRaw('FIELD(category, "Underweight", "Normal", "Overweight", "Obese")')
            ->get();

        return response()->json([
            'bp_status' => $bpStatus,
            'rbs_levels' => $rbsLevels,
            'bmi_analysis' => $bmiAnalysis,
        ]);
    }

    /**
     * Get lab diagnostics data
     */
    public function getLabDiagnostics(Request $request)
    {
        $query = Patient::query();
        $this->applyFilters($query, $request);

        // Common diagnoses
        $diagnoses = (clone $query)
            ->whereNotNull('diagnosis')
            ->select('diagnosis', DB::raw('COUNT(*) as count'))
            ->groupBy('diagnosis')
            ->orderByDesc('count')
            ->limit(10)
            ->get();

        // Lab tests advised (JSON field)
        $labTestsRaw = (clone $query)
            ->whereNotNull('lab_tests')
            ->pluck('lab_tests')
            ->flatten()
            ->countBy()
            ->sortDesc()
            ->take(10);

        $labTests = $labTestsRaw->map(function ($count, $test) {
            return ['name' => $test, 'count' => $count];
        })->values();

        // Sample collection status
        $sampleStatus = (clone $query)
            ->whereNotNull('sample_collected')
            ->select('sample_collected', DB::raw('COUNT(*) as count'))
            ->groupBy('sample_collected')
            ->get();

        return response()->json([
            'diagnoses' => $diagnoses,
            'lab_tests' => $labTests,
            'sample_status' => $sampleStatus,
        ]);
    }

    /**
     * Get treatment analytics
     */
    public function getTreatmentAnalytics(Request $request)
    {
        $query = Patient::query();
        $this->applyFilters($query, $request);

        // Common medications (from treatment field)
        $medications = (clone $query)
            ->whereNotNull('treatment')
            ->select('treatment', DB::raw('COUNT(*) as count'))
            ->groupBy('treatment')
            ->orderByDesc('count')
            ->limit(10)
            ->get();

        // Treatment duration analysis (extract from dosage if pattern exists)
        $treatmentDuration = (clone $query)
            ->whereNotNull('dosage')
            ->select(
                DB::raw('CASE
                    WHEN dosage LIKE "%1 week%" OR dosage LIKE "%7 days%" THEN "1 week"
                    WHEN dosage LIKE "%2 week%" OR dosage LIKE "%14 days%" THEN "2 weeks"
                    WHEN dosage LIKE "%1 month%" OR dosage LIKE "%30 days%" THEN "1 month"
                    WHEN dosage LIKE "%3 month%" OR dosage LIKE "%90 days%" THEN "3 months"
                    ELSE "Other"
                END as duration'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('duration')
            ->get();

        return response()->json([
            'medications' => $medications,
            'treatment_duration' => $treatmentDuration,
        ]);
    }

    /**
     * Get paginated patient list with filters
     */
    public function getPatients(Request $request)
    {
        $query = Patient::with(['country', 'state', 'district', 'taluka', 'createdBy']);
        $this->applyFilters($query, $request);

        $patients = $query
            ->orderBy('date', 'desc')
            ->paginate($request->input('per_page', 20));

        return response()->json($patients);
    }

    /**
     * Get states for a country
     */
    public function getStates($countryId)
    {
        $states = State::where('country_id', $countryId)
            ->active()
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json($states);
    }

    /**
     * Get districts for a state
     */
    public function getDistricts($stateId)
    {
        $districts = District::where('state_id', $stateId)
            ->active()
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json($districts);
    }

    /**
     * Get talukas for a district
     */
    public function getTalukas($districtId)
    {
        $talukas = Taluka::where('district_id', $districtId)
            ->active()
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json($talukas);
    }

    /**
     * Apply filters to query based on request parameters
     */
    private function applyFilters($query, Request $request)
    {
        // Date range filter
        if ($request->filled('date_from')) {
            $query->where('date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('date', '<=', $request->date_to);
        }

        // Year filter
        if ($request->filled('year')) {
            $query->whereYear('date', $request->year);
        }

        // Month filter (requires year)
        if ($request->filled('month') && $request->filled('year')) {
            $query->whereMonth('date', $request->month)
                  ->whereYear('date', $request->year);
        }

        // Gender filter
        if ($request->filled('gender') && $request->input('gender') !== '') {
            $query->where('sex', $request->gender);
        }

        // Age group filter
        if ($request->filled('age_group') && $request->input('age_group') !== '') {
            switch ($request->age_group) {
                case '0-17':
                    $query->where('age', '<', 18);
                    break;
                case '18-30':
                    $query->whereBetween('age', [18, 30]);
                    break;
                case '31-45':
                    $query->whereBetween('age', [31, 45]);
                    break;
                case '46-60':
                    $query->whereBetween('age', [46, 60]);
                    break;
                case '60+':
                    $query->where('age', '>', 60);
                    break;
            }
        }

        // Campaign type filter
        if ($request->filled('campaign_type_id') && $request->input('campaign_type_id') !== '') {
            $query->where('campaign_type_id', $request->campaign_type_id);
        }

        // Location filters (cascading)
        if ($request->filled('country_id') && $request->input('country_id') !== '') {
            $query->where('country_id', $request->country_id);
        }
        if ($request->filled('state_id') && $request->input('state_id') !== '') {
            $query->where('state_id', $request->state_id);
        }
        if ($request->filled('district_id') && $request->input('district_id') !== '') {
            $query->where('district_id', $request->district_id);
        }
        if ($request->filled('taluka_id') && $request->input('taluka_id') !== '') {
            $query->where('taluka_id', $request->taluka_id);
        }

        return $query;
    }
}
