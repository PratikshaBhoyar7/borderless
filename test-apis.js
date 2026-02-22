/**
 * Comprehensive API Testing Script for Borderless Analytics Dashboard
 * Tests all endpoints used by chart.html
 *
 * Usage: Open browser console and paste this entire script
 * Or: node test-apis.js (requires fetch polyfill in Node.js)
 */

// Configuration
const CONFIG = {
    BASE_URL: 'http://localhost:8000',
    LOGIN_EMAIL: 'admin@admin.com',
    LOGIN_PASSWORD: 'password',
    TIMEOUT: 10000, // 10 seconds
    VERBOSE: true
};

// Test results tracking
const results = {
    authentication: { status: 'pending', message: '' },
    endpoints: {}
};

// Color coding for console output
const colors = {
    reset: '\x1b[0m',
    red: '\x1b[31m',
    green: '\x1b[32m',
    yellow: '\x1b[33m',
    blue: '\x1b[36m',
    bold: '\x1b[1m'
};

// Logging helper
function log(message, type = 'info') {
    const timestamp = new Date().toLocaleTimeString();
    const prefix = {
        'info': `${colors.blue}ℹ${colors.reset}`,
        'success': `${colors.green}✓${colors.reset}`,
        'error': `${colors.red}✗${colors.reset}`,
        'warning': `${colors.yellow}⚠${colors.reset}`,
        'test': `${colors.bold}TEST${colors.reset}`
    }[type] || 'ℹ';

    console.log(`[${timestamp}] ${prefix} ${message}`);
}

// Fetch with timeout
async function fetchWithTimeout(url, options = {}, timeout = CONFIG.TIMEOUT) {
    const controller = new AbortController();
    const timeoutId = setTimeout(() => controller.abort(), timeout);

    try {
        const response = await fetch(url, {
            ...options,
            signal: controller.signal
        });
        clearTimeout(timeoutId);
        return response;
    } catch (error) {
        clearTimeout(timeoutId);
        if (error.name === 'AbortError') {
            throw new Error(`Request timeout after ${timeout}ms`);
        }
        throw error;
    }
}

// Extract CSRF token from HTML
function extractCsrfToken(html) {
    const match = html.match(/name="_token"\s+value="([^"]+)"/);
    return match ? match[1] : null;
}

// Step 1: Test basic connectivity
async function testConnectivity() {
    log('Testing basic connectivity...', 'test');
    try {
        const response = await fetchWithTimeout(`${CONFIG.BASE_URL}/`);
        if (response.ok) {
            log(`✓ Server accessible at ${CONFIG.BASE_URL}`, 'success');
            return true;
        } else {
            log(`✗ Server returned ${response.status}`, 'error');
            return false;
        }
    } catch (error) {
        log(`✗ Cannot reach server: ${error.message}`, 'error');
        return false;
    }
}

// Step 2: Test login and authentication
async function testAuthentication() {
    log('Testing authentication...', 'test');
    try {
        // Get CSRF token
        log('Extracting CSRF token from login page...', 'info');
        const loginPageResponse = await fetchWithTimeout(`${CONFIG.BASE_URL}/login`);
        const loginPageHtml = await loginPageResponse.text();
        const csrfToken = extractCsrfToken(loginPageHtml);

        if (!csrfToken) {
            log('✗ Could not extract CSRF token', 'error');
            results.authentication.status = 'failed';
            results.authentication.message = 'CSRF token extraction failed';
            return false;
        }

        log(`✓ CSRF token extracted: ${csrfToken.substring(0, 20)}...`, 'success');

        // Login
        log('Sending login request...', 'info');
        const loginFormData = new FormData();
        loginFormData.append('email', CONFIG.LOGIN_EMAIL);
        loginFormData.append('password', CONFIG.LOGIN_PASSWORD);
        loginFormData.append('_token', csrfToken);

        const loginResponse = await fetchWithTimeout(`${CONFIG.BASE_URL}/login`, {
            method: 'POST',
            credentials: 'include',
            body: loginFormData
        });

        if (loginResponse.ok || loginResponse.status === 302) {
            log(`✓ Login successful (Status: ${loginResponse.status})`, 'success');
            results.authentication.status = 'success';
            results.authentication.message = 'Authentication successful';
            return true;
        } else {
            log(`✗ Login failed with status ${loginResponse.status}`, 'error');
            results.authentication.status = 'failed';
            results.authentication.message = `Login failed with status ${loginResponse.status}`;
            return false;
        }
    } catch (error) {
        log(`✗ Authentication error: ${error.message}`, 'error');
        results.authentication.status = 'failed';
        results.authentication.message = error.message;
        return false;
    }
}

// Step 3: Test API endpoints
async function testEndpoint(name, endpoint, params = {}) {
    log(`Testing endpoint: ${endpoint}`, 'test');

    try {
        // Build URL with parameters
        const url = new URL(`${CONFIG.BASE_URL}${endpoint}`);
        Object.entries(params).forEach(([key, value]) => {
            if (value) url.searchParams.append(key, value);
        });

        log(`URL: ${url.toString()}`, 'info');

        // Make request
        const response = await fetchWithTimeout(url.toString(), {
            method: 'GET',
            credentials: 'include',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        log(`Response status: ${response.status}`, 'info');

        if (response.status === 401) {
            log(`✗ Unauthorized (401) - Session may have expired`, 'error');
            results.endpoints[name] = { status: 'failed', code: 401, message: 'Unauthorized' };
            return false;
        }

        if (!response.ok) {
            log(`✗ HTTP Error ${response.status}`, 'error');
            const errorText = await response.text();
            log(`Error details: ${errorText.substring(0, 200)}`, 'warning');
            results.endpoints[name] = { status: 'failed', code: response.status, message: errorText };
            return false;
        }

        const data = await response.json();

        // Validate response structure
        const isEmpty = typeof data === 'object' && Object.keys(data).length === 0;
        const isArray = Array.isArray(data);
        const hasData = (isArray && data.length > 0) || (!isArray && Object.keys(data).length > 0);

        if (isEmpty) {
            log(`⚠ Empty response (may indicate no data in database)`, 'warning');
        } else if (hasData) {
            log(`✓ Success - Received data`, 'success');
            const preview = isArray ? `${data.length} items` : `${Object.keys(data).length} fields`;
            log(`Data preview: ${preview}`, 'info');
        } else {
            log(`✓ Success - Response received`, 'success');
        }

        // Store detailed response info
        results.endpoints[name] = {
            status: 'success',
            code: response.status,
            message: 'OK',
            dataType: isArray ? 'array' : 'object',
            itemCount: isArray ? data.length : Object.keys(data).length,
            sampleData: JSON.stringify(data).substring(0, 500)
        };

        return true;
    } catch (error) {
        log(`✗ Error: ${error.message}`, 'error');
        results.endpoints[name] = { status: 'failed', message: error.message };
        return false;
    }
}

// Step 4: Test all endpoints
async function testAllEndpoints() {
    log('Testing all API endpoints...', 'test');
    console.log('\n');

    const endpoints = [
        {
            name: 'Stats (No Filter)',
            endpoint: '/admin/analytics/api/stats',
            params: {}
        },
        {
            name: 'Stats (Gender: Male)',
            endpoint: '/admin/analytics/api/stats',
            params: { gender: 'Male' }
        },
        {
            name: 'Stats (Gender: Female)',
            endpoint: '/admin/analytics/api/stats',
            params: { gender: 'Female' }
        },
        {
            name: 'Stats (Age Group: 18-30)',
            endpoint: '/admin/analytics/api/stats',
            params: { age_group: '18-30' }
        },
        {
            name: 'Demographics',
            endpoint: '/admin/analytics/api/demographics',
            params: {}
        },
        {
            name: 'Demographics (Gender: Male)',
            endpoint: '/admin/analytics/api/demographics',
            params: { gender: 'Male' }
        },
        {
            name: 'Health Metrics',
            endpoint: '/admin/analytics/api/health-metrics',
            params: {}
        },
        {
            name: 'Health Metrics (Age Group: 60+)',
            endpoint: '/admin/analytics/api/health-metrics',
            params: { age_group: '60+' }
        },
        {
            name: 'Patients (Page 1)',
            endpoint: '/admin/analytics/api/patients',
            params: { page: 1, per_page: 10 }
        },
        {
            name: 'Patients (Page 2)',
            endpoint: '/admin/analytics/api/patients',
            params: { page: 2, per_page: 10 }
        },
        {
            name: 'Patients (Gender: Female, Page 1)',
            endpoint: '/admin/analytics/api/patients',
            params: { gender: 'Female', page: 1, per_page: 10 }
        },
        {
            name: 'States',
            endpoint: '/admin/analytics/api/states/1',
            params: {}
        },
        {
            name: 'Districts',
            endpoint: '/admin/analytics/api/districts/1',
            params: {}
        },
        {
            name: 'Talukas',
            endpoint: '/admin/analytics/api/talukas/1',
            params: {}
        }
    ];

    for (const test of endpoints) {
        await testEndpoint(test.name, test.endpoint, test.params);
        console.log('');
    }
}

// Step 5: Generate test report
function generateReport() {
    console.log('\n' + '='.repeat(80));
    console.log(`${colors.bold}TEST REPORT${colors.reset}`);
    console.log('='.repeat(80) + '\n');

    // Authentication status
    console.log(`${colors.bold}Authentication:${colors.reset}`);
    const authColor = results.authentication.status === 'success' ? colors.green : colors.red;
    console.log(`  ${authColor}${results.authentication.status.toUpperCase()}${colors.reset} - ${results.authentication.message}`);
    console.log('');

    // Endpoint summary
    console.log(`${colors.bold}Endpoint Tests:${colors.reset}`);
    const endpointNames = Object.keys(results.endpoints);
    const successCount = endpointNames.filter(name => results.endpoints[name].status === 'success').length;
    const failureCount = endpointNames.filter(name => results.endpoints[name].status === 'failed').length;

    console.log(`  Total: ${endpointNames.length}`);
    console.log(`  ${colors.green}Passed: ${successCount}${colors.reset}`);
    console.log(`  ${colors.red}Failed: ${failureCount}${colors.reset}`);
    console.log('');

    // Detailed results
    console.log(`${colors.bold}Detailed Results:${colors.reset}`);
    endpointNames.forEach(name => {
        const result = results.endpoints[name];
        const statusSymbol = result.status === 'success' ? `${colors.green}✓${colors.reset}` : `${colors.red}✗${colors.reset}`;
        console.log(`  ${statusSymbol} ${name}`);

        if (result.status === 'success') {
            console.log(`      Code: ${result.code} | Type: ${result.dataType} | Items: ${result.itemCount}`);
        } else {
            console.log(`      Code: ${result.code || 'N/A'} | Error: ${result.message}`);
        }
    });

    console.log('\n' + '='.repeat(80));
    console.log(`${colors.bold}Overall Status: ${successCount === endpointNames.length ? colors.green + 'ALL TESTS PASSED' : colors.yellow + 'SOME TESTS FAILED'}${colors.reset}`);
    console.log('='.repeat(80) + '\n');

    return {
        totalTests: endpointNames.length,
        passed: successCount,
        failed: failureCount,
        allPassed: failureCount === 0
    };
}

// Main test runner
async function runAllTests() {
    console.clear();
    console.log(`${colors.bold}${colors.blue}╔════════════════════════════════════════════════════════╗${colors.reset}`);
    console.log(`${colors.bold}${colors.blue}║  Borderless Analytics API - Comprehensive Test Suite    ║${colors.reset}`);
    console.log(`${colors.bold}${colors.blue}╚════════════════════════════════════════════════════════╝${colors.reset}\n`);

    log(`Testing API at: ${CONFIG.BASE_URL}`, 'info');
    log(`Using credentials: ${CONFIG.LOGIN_EMAIL}`, 'info');
    console.log('\n');

    try {
        // Test 1: Connectivity
        const isConnected = await testConnectivity();
        console.log('');

        if (!isConnected) {
            log('Cannot proceed - server is not accessible', 'error');
            return;
        }

        // Test 2: Authentication
        const isAuthenticated = await testAuthentication();
        console.log('');

        if (!isAuthenticated) {
            log('Cannot proceed - authentication failed', 'error');
            return;
        }

        // Test 3: All endpoints
        await testAllEndpoints();

        // Test 4: Generate report
        const summary = generateReport();

        // Save results to window for inspection
        window.testResults = results;
        log('Results saved to window.testResults', 'info');

    } catch (error) {
        log(`Fatal error: ${error.message}`, 'error');
    }
}

// Export for use
if (typeof module !== 'undefined' && module.exports) {
    module.exports = { runAllTests, testEndpoint, testAuthentication };
}

// Auto-run if in browser
if (typeof window !== 'undefined') {
    console.log('Test suite loaded. Run: runAllTests()');
}
