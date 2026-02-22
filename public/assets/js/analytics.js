// Global variables
let charts = {};
let currentFilters = {};
const colors = {
    primary: '#4e73df',
    success: '#1cc88a',
    danger: '#e74a3b',
    warning: '#f6c23e',
    info: '#36b9cc',
    secondary: '#858796',
    light: '#f8f9fc'
};

// Initialize on DOM load
document.addEventListener('DOMContentLoaded', function() {
    initializeTabs();
    initializeFilters();
    initializeCascadingDropdowns();
    loadOverviewData();
});

// ==================== TAB MANAGEMENT ====================

function initializeTabs() {
    const tabButtons = document.querySelectorAll('.tab-button');

    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const tabId = this.getAttribute('data-tab');
            switchTab(tabId);
        });
    });
}

function switchTab(tabId) {
    // Update active states
    document.querySelectorAll('.tab-button').forEach(btn => btn.classList.remove('active'));
    document.querySelectorAll('.tab-pane').forEach(pane => pane.classList.remove('active'));

    document.querySelector(`[data-tab="${tabId}"]`).classList.add('active');
    document.getElementById(tabId).classList.add('active');

    // Load tab-specific data
    loadTabData(tabId);
}

function loadTabData(tabId) {
    const params = new URLSearchParams(currentFilters);

    switch (tabId) {
        case 'overview':
            loadKPIStats(params);
            loadRegistrationTrend(params);
            break;
        case 'demographics':
            loadDemographics(params);
            break;
        case 'health':
            loadHealthMetrics(params);
            break;
        case 'lab':
            loadLabDiagnostics(params);
            break;
        case 'treatment':
            loadTreatmentAnalytics(params);
            break;
        case 'patients':
            loadPatients(params, 1);
            break;
    }
}

// ==================== FILTER MANAGEMENT ====================

function initializeFilters() {
    document.getElementById('applyFilters').addEventListener('click', applyFilters);
    document.getElementById('resetFilters').addEventListener('click', resetFilters);
}

function applyFilters() {
    currentFilters = {
        date_from: document.getElementById('dateFrom').value,
        date_to: document.getElementById('dateTo').value,
        gender: document.getElementById('genderFilter').value,
        age_group: document.getElementById('ageGroupFilter').value,
        campaign_type_id: document.getElementById('campaignTypeFilter').value,
        country_id: document.getElementById('countryFilter').value,
        state_id: document.getElementById('stateFilter').value,
        district_id: document.getElementById('districtFilter').value,
        taluka_id: document.getElementById('talukaFilter').value,
    };

    // Reload current tab data with filters
    const activeTab = document.querySelector('.tab-pane.active').id;
    loadTabData(activeTab);
}

function resetFilters() {
    // Clear all filter inputs
    document.getElementById('dateFrom').value = '';
    document.getElementById('dateTo').value = '';
    document.getElementById('genderFilter').value = '';
    document.getElementById('ageGroupFilter').value = '';
    document.getElementById('campaignTypeFilter').value = '';
    document.getElementById('countryFilter').value = '';
    document.getElementById('stateFilter').value = '';
    document.getElementById('districtFilter').value = '';
    document.getElementById('talukaFilter').value = '';

    // Disable cascading selects
    disableCascadeSelects();

    // Clear filters and reload
    currentFilters = {};
    applyFilters();
}

// ==================== CASCADING DROPDOWNS ====================

function initializeCascadingDropdowns() {
    document.getElementById('countryFilter').addEventListener('change', function() {
        // Clear state, district, taluka
        document.getElementById('stateFilter').value = '';
        document.getElementById('districtFilter').value = '';
        document.getElementById('talukaFilter').value = '';
        disableCascadeSelects();

        if (this.value) {
            loadStates(this.value);
        }
    });

    document.getElementById('stateFilter').addEventListener('change', function() {
        // Clear district, taluka
        document.getElementById('districtFilter').value = '';
        document.getElementById('talukaFilter').value = '';
        document.getElementById('districtFilter').disabled = !this.value;
        document.getElementById('talukaFilter').disabled = true;

        if (this.value) {
            loadDistricts(this.value);
        }
    });

    document.getElementById('districtFilter').addEventListener('change', function() {
        // Clear taluka
        document.getElementById('talukaFilter').value = '';
        document.getElementById('talukaFilter').disabled = !this.value;

        if (this.value) {
            loadTalukas(this.value);
        }
    });
}

function disableCascadeSelects() {
    document.getElementById('stateFilter').disabled = true;
    document.getElementById('districtFilter').disabled = true;
    document.getElementById('talukaFilter').disabled = true;
}

async function loadStates(countryId) {
    try {
        const response = await fetch(`/admin/analytics/api/states/${countryId}`);
        const states = await response.json();

        const stateSelect = document.getElementById('stateFilter');
        stateSelect.innerHTML = '<option value="">Select State</option>';
        states.forEach(state => {
            const option = document.createElement('option');
            option.value = state.id;
            option.textContent = state.name;
            stateSelect.appendChild(option);
        });
        stateSelect.disabled = false;
    } catch (error) {
        console.error('Error loading states:', error);
    }
}

async function loadDistricts(stateId) {
    try {
        const response = await fetch(`/admin/analytics/api/districts/${stateId}`);
        const districts = await response.json();

        const districtSelect = document.getElementById('districtFilter');
        districtSelect.innerHTML = '<option value="">Select District</option>';
        districts.forEach(district => {
            const option = document.createElement('option');
            option.value = district.id;
            option.textContent = district.name;
            districtSelect.appendChild(option);
        });
        districtSelect.disabled = false;
    } catch (error) {
        console.error('Error loading districts:', error);
    }
}

async function loadTalukas(districtId) {
    try {
        const response = await fetch(`/admin/analytics/api/talukas/${districtId}`);
        const talukas = await response.json();

        const talukaSelect = document.getElementById('talukaFilter');
        talukaSelect.innerHTML = '<option value="">Select Taluka</option>';
        talukas.forEach(taluka => {
            const option = document.createElement('option');
            option.value = taluka.id;
            option.textContent = taluka.name;
            talukaSelect.appendChild(option);
        });
        talukaSelect.disabled = false;
    } catch (error) {
        console.error('Error loading talukas:', error);
    }
}

// ==================== AJAX API CALLS ====================

async function loadKPIStats(params) {
    try {
        const response = await fetch(`/admin/analytics/api/stats?${params}`);
        const stats = await response.json();
        renderKPICards(stats);
    } catch (error) {
        console.error('Error loading KPI stats:', error);
    }
}

async function loadRegistrationTrend(params) {
    try {
        const response = await fetch(`/admin/analytics/api/registration-trend?period=month&${params}`);
        const data = await response.json();
        renderRegistrationTrendChart(data);
    } catch (error) {
        console.error('Error loading registration trend:', error);
    }
}

async function loadDemographics(params) {
    try {
        const response = await fetch(`/admin/analytics/api/demographics?${params}`);
        const data = await response.json();

        renderGenderChart(data.gender);
        renderAgeGroupChart(data.age_groups);
        renderTopVillagesChart(data.top_villages);
        renderAgeByGenderChart(data.age_by_gender);
    } catch (error) {
        console.error('Error loading demographics:', error);
    }
}

async function loadHealthMetrics(params) {
    try {
        const response = await fetch(`/admin/analytics/api/health-metrics?${params}`);
        const data = await response.json();

        renderBPStatusChart(data.bp_status);
        renderRBSLevelsChart(data.rbs_levels);
        renderBMIChart(data.bmi_analysis);
    } catch (error) {
        console.error('Error loading health metrics:', error);
    }
}

async function loadLabDiagnostics(params) {
    try {
        const response = await fetch(`/admin/analytics/api/lab-diagnostics?${params}`);
        const data = await response.json();

        renderDiagnosesChart(data.diagnoses);
        renderLabTestsChart(data.lab_tests);
        renderSampleStatusChart(data.sample_status);
    } catch (error) {
        console.error('Error loading lab diagnostics:', error);
    }
}

async function loadTreatmentAnalytics(params) {
    try {
        const response = await fetch(`/admin/analytics/api/treatment-analytics?${params}`);
        const data = await response.json();

        renderMedicationsChart(data.medications);
        renderTreatmentDurationChart(data.treatment_duration);
    } catch (error) {
        console.error('Error loading treatment analytics:', error);
    }
}

async function loadPatients(params, page = 1) {
    try {
        const response = await fetch(`/admin/analytics/api/patients?page=${page}&per_page=20&${params}`);
        const data = await response.json();
        renderPatientsTable(data);
        renderPatientsPagination(data);
    } catch (error) {
        console.error('Error loading patients:', error);
    }
}

// ==================== KPI CARDS RENDERING ====================

function renderKPICards(stats) {
    const html = `
        <div class="kpi-card blue">
            <div class="kpi-value">${stats.total_patients}</div>
            <div class="kpi-label">Total Patients</div>
        </div>
        <div class="kpi-card green">
            <div class="kpi-value">${stats.male_patients}</div>
            <div class="kpi-label">Male Patients</div>
        </div>
        <div class="kpi-card orange">
            <div class="kpi-value">${stats.female_patients}</div>
            <div class="kpi-label">Female Patients</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-value">${stats.avg_age}</div>
            <div class="kpi-label">Average Age</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-value">${stats.samples_collected}</div>
            <div class="kpi-label">Samples Collected</div>
        </div>
        <div class="kpi-card red">
            <div class="kpi-value">${stats.referrals_made}</div>
            <div class="kpi-label">Referrals Made</div>
        </div>
    `;

    document.getElementById('kpiCards').innerHTML = html;
}

// ==================== CHART RENDERING FUNCTIONS ====================

function renderRegistrationTrendChart(data) {
    destroyChart('registrationTrend');

    const ctx = document.getElementById('registrationTrendChart').getContext('2d');
    charts.registrationTrend = new Chart(ctx, {
        type: 'line',
        data: {
            labels: data.map(d => d.period),
            datasets: [{
                label: 'Patient Registrations',
                data: data.map(d => d.count),
                borderColor: colors.primary,
                backgroundColor: 'rgba(78, 115, 223, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4,
                pointRadius: 4,
                pointBackgroundColor: colors.primary,
                pointBorderColor: '#fff',
                pointBorderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom'
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    borderColor: colors.primary,
                    borderWidth: 1,
                    callbacks: {
                        label: function(context) {
                            return 'Patients: ' + context.parsed.y;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
}

function renderGenderChart(data) {
    destroyChart('gender');

    const ctx = document.getElementById('genderChart').getContext('2d');
    const labels = data.map(d => d.sex || 'Unknown');
    const values = data.map(d => d.count);

    charts.gender = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: values,
                backgroundColor: [colors.primary, colors.success, colors.warning],
                borderColor: '#fff',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
}

function renderAgeGroupChart(data) {
    destroyChart('ageGroup');

    const ctx = document.getElementById('ageGroupChart').getContext('2d');
    const labels = data.map(d => d.age_group);
    const values = data.map(d => d.count);

    charts.ageGroup = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Number of Patients',
                data: values,
                backgroundColor: colors.primary,
                borderColor: '#2e59a7',
                borderWidth: 1
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom'
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            }
        }
    });
}

function renderTopVillagesChart(data) {
    destroyChart('topVillages');

    const ctx = document.getElementById('topVillagesChart').getContext('2d');
    const labels = data.map(d => d.village).slice(0, 10);
    const values = data.map(d => d.count).slice(0, 10);

    charts.topVillages = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Patient Count',
                data: values,
                backgroundColor: colors.success,
                borderColor: '#15934a',
                borderWidth: 1
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom'
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            }
        }
    });
}

function renderAgeByGenderChart(data) {
    destroyChart('ageByGender');

    const ctx = document.getElementById('ageByGenderChart').getContext('2d');

    // Prepare data by age group
    const ageGroups = ['0-17', '18-30', '31-45', '46-60', '60+'];
    const maleData = {};
    const femaleData = {};
    const otherData = {};

    ageGroups.forEach(ag => {
        maleData[ag] = 0;
        femaleData[ag] = 0;
        otherData[ag] = 0;
    });

    data.forEach(d => {
        if (d.sex === 'Male') maleData[d.age_group] = d.count;
        else if (d.sex === 'Female') femaleData[d.age_group] = d.count;
        else otherData[d.age_group] = d.count;
    });

    charts.ageByGender = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ageGroups,
            datasets: [
                {
                    label: 'Male',
                    data: ageGroups.map(ag => maleData[ag]),
                    backgroundColor: colors.info
                },
                {
                    label: 'Female',
                    data: ageGroups.map(ag => femaleData[ag]),
                    backgroundColor: colors.danger
                },
                {
                    label: 'Other',
                    data: ageGroups.map(ag => otherData[ag]),
                    backgroundColor: colors.secondary
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            }
        }
    });
}

function renderBPStatusChart(data) {
    destroyChart('bpStatus');

    const ctx = document.getElementById('bpStatusChart').getContext('2d');
    const labels = data.map(d => d.status);
    const values = data.map(d => d.count);

    const bgColors = labels.map(label => {
        if (label === 'Normal') return colors.success;
        if (label === 'Prehypertension') return colors.warning;
        if (label === 'Hypertension') return colors.danger;
        return colors.secondary;
    });

    charts.bpStatus = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: values,
                backgroundColor: bgColors,
                borderColor: '#fff',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
}

function renderRBSLevelsChart(data) {
    destroyChart('rbsLevels');

    const ctx = document.getElementById('rbsLevelsChart').getContext('2d');
    const labels = data.map(d => d.level);
    const values = data.map(d => d.count);

    const bgColors = labels.map(label => {
        if (label.includes('Normal')) return colors.success;
        if (label.includes('Prediabetic')) return colors.warning;
        if (label.includes('Diabetic')) return colors.danger;
        return colors.secondary;
    });

    charts.rbsLevels = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Number of Patients',
                data: values,
                backgroundColor: bgColors,
                borderWidth: 0
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            }
        }
    });
}

function renderBMIChart(data) {
    destroyChart('bmi');

    const ctx = document.getElementById('bmiChart').getContext('2d');
    const labels = data.map(d => d.category);
    const values = data.map(d => d.count);

    const bgColors = labels.map(label => {
        if (label === 'Underweight') return colors.info;
        if (label === 'Normal') return colors.success;
        if (label === 'Overweight') return colors.warning;
        if (label === 'Obese') return colors.danger;
        return colors.secondary;
    });

    charts.bmi = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Number of Patients',
                data: values,
                backgroundColor: bgColors,
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            }
        }
    });
}

function renderDiagnosesChart(data) {
    destroyChart('diagnoses');

    const ctx = document.getElementById('diagnosesChart').getContext('2d');
    const labels = data.map(d => d.diagnosis).slice(0, 10);
    const values = data.map(d => d.count).slice(0, 10);

    charts.diagnoses = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: values,
                backgroundColor: [
                    colors.primary, colors.success, colors.danger, colors.warning, colors.info,
                    '#6c757d', '#20c997', '#fd7e14', '#e83e8c', '#0d6efd'
                ],
                borderColor: '#fff',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
}

function renderLabTestsChart(data) {
    destroyChart('labTests');

    const ctx = document.getElementById('labTestsChart').getContext('2d');
    const labels = data.slice(0, 10).map(d => d.name);
    const values = data.slice(0, 10).map(d => d.count);

    charts.labTests = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: values,
                backgroundColor: [
                    colors.primary, colors.success, colors.danger, colors.warning, colors.info,
                    '#6c757d', '#20c997', '#fd7e14', '#e83e8c', '#0d6efd'
                ],
                borderColor: '#fff',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
}

function renderSampleStatusChart(data) {
    destroyChart('sampleStatus');

    const ctx = document.getElementById('sampleStatusChart').getContext('2d');
    const labels = data.map(d => d.sample_collected || 'Unknown');
    const values = data.map(d => d.count);

    const bgColors = labels.map(label => {
        if (label === 'Yes') return colors.success;
        if (label === 'No') return colors.danger;
        return colors.secondary;
    });

    charts.sampleStatus = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: values,
                backgroundColor: bgColors,
                borderColor: '#fff',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
}

function renderMedicationsChart(data) {
    destroyChart('medications');

    const ctx = document.getElementById('medicationsChart').getContext('2d');
    const labels = data.map(d => d.treatment).slice(0, 10);
    const values = data.map(d => d.count).slice(0, 10);

    charts.medications = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Patient Count',
                data: values,
                backgroundColor: colors.primary,
                borderColor: '#2e59a7',
                borderWidth: 1
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom'
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            }
        }
    });
}

function renderTreatmentDurationChart(data) {
    destroyChart('treatmentDuration');

    const ctx = document.getElementById('treatmentDurationChart').getContext('2d');
    const labels = data.map(d => d.duration);
    const values = data.map(d => d.count);

    charts.treatmentDuration = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Number of Patients',
                data: values,
                backgroundColor: colors.info,
                borderColor: '#0d6efd',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            }
        }
    });
}

// ==================== PATIENTS TABLE ====================

function renderPatientsTable(data) {
    const tbody = document.getElementById('patientsTableBody');
    let html = '';

    if (data.data && data.data.length > 0) {
        data.data.forEach(patient => {
            html += `
                <tr>
                    <td>${patient.serial_number || '-'}</td>
                    <td>${patient.patient_name || '-'}</td>
                    <td>${patient.age || '-'}</td>
                    <td>${patient.sex || '-'}</td>
                    <td>${patient.village || '-'}</td>
                    <td>${[patient.taluka?.name, patient.district?.name, patient.state?.name]
                        .filter(v => v)
                        .join(', ') || '-'}</td>
                    <td>${patient.date || '-'}</td>
                    <td>${patient.bp || '-'}</td>
                    <td>${patient.rbs || '-'}</td>
                    <td>${patient.diagnosis ? patient.diagnosis.substring(0, 30) + '...' : '-'}</td>
                </tr>
            `;
        });
    } else {
        html = '<tr><td colspan="10" style="text-align: center; padding: 20px;">No data found</td></tr>';
    }

    tbody.innerHTML = html;
}

function renderPatientsPagination(data) {
    const container = document.getElementById('patientsPagination');
    let html = '';

    if (data.last_page > 1) {
        // Previous button
        if (data.current_page > 1) {
            html += `<button class="pagination-btn" onclick="loadPatients(new URLSearchParams(currentFilters), ${data.current_page - 1})">Previous</button>`;
        }

        // Page numbers
        for (let i = 1; i <= data.last_page; i++) {
            if (i === data.current_page) {
                html += `<button class="pagination-btn active">${i}</button>`;
            } else if (i === 1 || i === data.last_page || (i >= data.current_page - 1 && i <= data.current_page + 1)) {
                html += `<button class="pagination-btn" onclick="loadPatients(new URLSearchParams(currentFilters), ${i})">${i}</button>`;
            } else if (i === 2 || i === data.last_page - 1) {
                html += `<span style="padding: 0 5px;">...</span>`;
            }
        }

        // Next button
        if (data.current_page < data.last_page) {
            html += `<button class="pagination-btn" onclick="loadPatients(new URLSearchParams(currentFilters), ${data.current_page + 1})">Next</button>`;
        }
    }

    container.innerHTML = html;
}

// ==================== HELPER FUNCTIONS ====================

function destroyChart(chartKey) {
    if (charts[chartKey]) {
        charts[chartKey].destroy();
        charts[chartKey] = null;
    }
}

function loadOverviewData() {
    loadTabData('overview');
}
