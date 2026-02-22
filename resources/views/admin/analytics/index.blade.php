@extends('layouts.admin')

@section('page-title', 'Patient Health Analytics')

@push('styles')
<style>
    /* Filter buttons styling */
    .filter-buttons {
        display: flex;
        gap: 10px;
        margin-top: 15px;
    }

    .filter-buttons button {
        padding: 10px 20px;
        border: none;
        border-radius: 6px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .filter-buttons .btn-apply {
        background-color: #4e73df;
        color: white;
    }

    .filter-buttons .btn-apply:hover {
        background-color: #2e59a7;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(78, 115, 223, 0.3);
    }

    .filter-buttons .btn-reset {
        background-color: #e3e6f0;
        color: #2e59a7;
    }

    .filter-buttons .btn-reset:hover {
        background-color: #d8dce6;
    }

    /* Chart grid layouts */
    .charts-grid-2 {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(500px, 1fr));
        gap: 25px;
    }

    .charts-grid-3 {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
        gap: 25px;
    }

    @media (max-width: 1024px) {
        .charts-grid-2 {
            grid-template-columns: 1fr;
        }

        .charts-grid-3 {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .charts-grid-2,
        .charts-grid-3 {
            grid-template-columns: 1fr;
        }

        .filter-buttons {
            flex-direction: column;
        }

        .filter-buttons button {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endpush

@section('admin-content')
<div class="analytics-container">
    <!-- Filter Section -->
    <div class="filter-section">
        <h3><i class="bi bi-funnel me-2"></i>Data Filters</h3>

        <form id="analyticsFilters">
            <div class="filter-grid">
                <!-- Date Range -->
                <div class="filter-group">
                    <label for="dateFrom">From Date</label>
                    <input type="date" id="dateFrom" name="date_from" class="form-control">
                </div>

                <div class="filter-group">
                    <label for="dateTo">To Date</label>
                    <input type="date" id="dateTo" name="date_to" class="form-control">
                </div>

                <!-- Gender Filter -->
                <div class="filter-group">
                    <label for="genderFilter">Gender</label>
                    <select id="genderFilter" name="gender" class="form-control">
                        <option value="">All Genders</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>
                </div>

                <!-- Age Group Filter -->
                <div class="filter-group">
                    <label for="ageGroupFilter">Age Group</label>
                    <select id="ageGroupFilter" name="age_group" class="form-control">
                        <option value="">All Ages</option>
                        <option value="0-17">0-17 years</option>
                        <option value="18-30">18-30 years</option>
                        <option value="31-45">31-45 years</option>
                        <option value="46-60">46-60 years</option>
                        <option value="60+">60+ years</option>
                    </select>
                </div>

                <!-- Campaign Type Filter -->
                <div class="filter-group">
                    <label for="campaignTypeFilter">Campaign Type</label>
                    <select id="campaignTypeFilter" name="campaign_type_id" class="form-control">
                        <option value="">All Campaign Types</option>
                        @foreach($campaignTypes as $campaign)
                            <option value="{{ $campaign->id }}">{{ $campaign->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Country Filter -->
                <div class="filter-group">
                    <label for="countryFilter">Country</label>
                    <select id="countryFilter" name="country_id" class="form-control">
                        <option value="">All Countries</option>
                        @foreach($countries as $country)
                            <option value="{{ $country->id }}">{{ $country->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- State Filter -->
                <div class="filter-group">
                    <label for="stateFilter">State</label>
                    <select id="stateFilter" name="state_id" class="form-control" disabled>
                        <option value="">Select Country First</option>
                    </select>
                </div>

                <!-- District Filter -->
                <div class="filter-group">
                    <label for="districtFilter">District</label>
                    <select id="districtFilter" name="district_id" class="form-control" disabled>
                        <option value="">Select State First</option>
                    </select>
                </div>

                <!-- Taluka Filter -->
                <div class="filter-group">
                    <label for="talukaFilter">Taluka</label>
                    <select id="talukaFilter" name="taluka_id" class="form-control" disabled>
                        <option value="">Select District First</option>
                    </select>
                </div>
            </div>

            <div class="filter-buttons">
                <button type="button" id="applyFilters" class="btn-apply">
                    <i class="bi bi-check-circle"></i> Apply Filters
                </button>
                <button type="button" id="resetFilters" class="btn-reset">
                    <i class="bi bi-arrow-counterclockwise"></i> Reset
                </button>
            </div>
        </form>
    </div>

    <!-- Tabs Container -->
    <div class="tabs-container">
        <div class="tab-header">
            <button class="tab-button active" data-tab="overview">
                <i class="bi bi-speedometer"></i> Overview
            </button>
            <button class="tab-button" data-tab="demographics">
                <i class="bi bi-people"></i> Demographics
            </button>
            <button class="tab-button" data-tab="health">
                <i class="bi bi-heart-pulse"></i> Health Metrics
            </button>
            <button class="tab-button" data-tab="lab">
                <i class="bi bi-clipboard2-pulse"></i> Lab & Diagnostics
            </button>
            <button class="tab-button" data-tab="treatment">
                <i class="bi bi-capsule"></i> Treatment
            </button>
            <button class="tab-button" data-tab="patients">
                <i class="bi bi-person-lines-fill"></i> Patients
            </button>
        </div>

        <div class="tab-content">
            <!-- ==================== OVERVIEW TAB ==================== -->
            <div id="overview" class="tab-pane active">
                <!-- KPI Cards -->
                <div class="kpi-cards" id="kpiCards">
                    <div class="loading-spinner">
                        <i class="bi bi-arrow-clockwise"></i>
                    </div>
                </div>

                <!-- Registration Trend Chart -->
                <div class="chart-container">
                    <div class="chart-title">
                        <i class="bi bi-graph-up"></i>
                        Patient Registration Trend (Last 30 Days)
                    </div>
                    <div class="chart-wrapper">
                        <canvas id="registrationTrendChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- ==================== DEMOGRAPHICS TAB ==================== -->
            <div id="demographics" class="tab-pane">
                <!-- Gender & Age Distribution -->
                <div class="charts-grid-2">
                    <!-- Gender Distribution -->
                    <div class="chart-container">
                        <div class="chart-title">
                            <i class="bi bi-gender-ambiguous"></i>
                            Gender Distribution
                        </div>
                        <div class="chart-wrapper small">
                            <canvas id="genderChart"></canvas>
                        </div>
                    </div>

                    <!-- Age Group Distribution -->
                    <div class="chart-container">
                        <div class="chart-title">
                            <i class="bi bi-calendar-range"></i>
                            Age Group Distribution
                        </div>
                        <div class="chart-wrapper small">
                            <canvas id="ageGroupChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Top Villages -->
                <div class="chart-container">
                    <div class="chart-title">
                        <i class="bi bi-geo-alt"></i>
                        Top 10 Villages by Patient Count
                    </div>
                    <div class="chart-wrapper">
                        <canvas id="topVillagesChart"></canvas>
                    </div>
                </div>

                <!-- Age by Gender -->
                <div class="chart-container">
                    <div class="chart-title">
                        <i class="bi bi-bar-chart-steps"></i>
                        Age Distribution by Gender
                    </div>
                    <div class="chart-wrapper">
                        <canvas id="ageByGenderChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- ==================== HEALTH METRICS TAB ==================== -->
            <div id="health" class="tab-pane">
                <!-- BP, RBS, BMI -->
                <div class="charts-grid-3">
                    <!-- BP Status -->
                    <div class="chart-container">
                        <div class="chart-title">
                            <i class="bi bi-activity"></i>
                            Blood Pressure Status
                        </div>
                        <div class="chart-wrapper small">
                            <canvas id="bpStatusChart"></canvas>
                        </div>
                    </div>

                    <!-- RBS Levels -->
                    <div class="chart-container">
                        <div class="chart-title">
                            <i class="bi bi-droplet"></i>
                            RBS Levels Analysis
                        </div>
                        <div class="chart-wrapper small">
                            <canvas id="rbsLevelsChart"></canvas>
                        </div>
                    </div>

                    <!-- BMI Analysis -->
                    <div class="chart-container">
                        <div class="chart-title">
                            <i class="bi bi-person-bounding-box"></i>
                            BMI Analysis
                        </div>
                        <div class="chart-wrapper small">
                            <canvas id="bmiChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ==================== LAB & DIAGNOSTICS TAB ==================== -->
            <div id="lab" class="tab-pane">
                <!-- Diagnoses, Lab Tests, Sample Status -->
                <div class="charts-grid-3">
                    <!-- Common Diagnoses -->
                    <div class="chart-container">
                        <div class="chart-title">
                            <i class="bi bi-clipboard-plus"></i>
                            Common Diagnoses (Top 10)
                        </div>
                        <div class="chart-wrapper">
                            <canvas id="diagnosesChart"></canvas>
                        </div>
                    </div>

                    <!-- Lab Tests -->
                    <div class="chart-container">
                        <div class="chart-title">
                            <i class="bi bi-file-medical"></i>
                            Lab Tests Advised (Top 10)
                        </div>
                        <div class="chart-wrapper">
                            <canvas id="labTestsChart"></canvas>
                        </div>
                    </div>

                    <!-- Sample Collection -->
                    <div class="chart-container">
                        <div class="chart-title">
                            <i class="bi bi-eyedropper"></i>
                            Sample Collection Status
                        </div>
                        <div class="chart-wrapper small">
                            <canvas id="sampleStatusChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ==================== TREATMENT TAB ==================== -->
            <div id="treatment" class="tab-pane">
                <!-- Common Medications -->
                <div class="chart-container">
                    <div class="chart-title">
                        <i class="bi bi-capsule-pill"></i>
                        Common Medications (Top 10)
                    </div>
                    <div class="chart-wrapper">
                        <canvas id="medicationsChart"></canvas>
                    </div>
                </div>

                <!-- Treatment Duration -->
                <div class="chart-container">
                    <div class="chart-title">
                        <i class="bi bi-hourglass-split"></i>
                        Treatment Duration Distribution
                    </div>
                    <div class="chart-wrapper">
                        <canvas id="treatmentDurationChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- ==================== PATIENTS TAB ==================== -->
            <div id="patients" class="tab-pane">
                <div class="chart-container">
                    <div class="chart-title">
                        <i class="bi bi-table"></i>
                        Patient Records
                    </div>

                    <div class="data-table-container">
                        <table class="data-table" id="patientsTable">
                            <thead>
                                <tr>
                                    <th>Serial No.</th>
                                    <th>Name</th>
                                    <th>Age</th>
                                    <th>Gender</th>
                                    <th>Village</th>
                                    <th>Location</th>
                                    <th>Date</th>
                                    <th>BP</th>
                                    <th>RBS</th>
                                    <th>Diagnosis</th>
                                </tr>
                            </thead>
                            <tbody id="patientsTableBody">
                                <tr>
                                    <td colspan="10" style="text-align: center; padding: 40px;">
                                        <div class="loading-spinner">
                                            <i class="bi bi-arrow-clockwise"></i>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div id="patientsPagination" class="pagination-container"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script src="{{ asset('assets/js/analytics.js') }}"></script>
@endpush
