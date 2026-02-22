<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Patient Management System - @yield('title', 'Admin Dashboard')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}">

    @stack('styles')
</head>
<body>
    <div class="admin-wrapper">
        <!-- Sidebar Navigation -->
        <div class="sidebar">
            <div class="sidebar-header">
                <a href="{{ route('admin.dashboard') }}" class="sidebar-brand">
                    <i class="bi bi-hospital"></i>
                    <h3>Healthcare Admin</h3>
                </a>
                <button class="sidebar-toggle">
                    <i class="bi bi-chevron-left"></i>
                </button>
            </div>

            <ul class="sidebar-menu">
                <!-- Dashboard -->
                @if(Auth::user()->hasPermission('view_dashboard'))
                    <li>
                        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="bi bi-speedometer2"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                @endif

                <!-- Administration (Users, Roles, Activity Logs) -->
                @if(Auth::user()->hasPermission('users_view') || Auth::user()->hasPermission('roles_view') || Auth::user()->hasPermission('activity_logs_view'))
                    <li class="sidebar-menu-header">Administration</li>

                    @if(Auth::user()->hasPermission('users_view'))
                        <li>
                            <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                                <i class="bi bi-people-fill"></i>
                                <span>Users</span>
                            </a>
                        </li>
                    @endif

                    @if(Auth::user()->hasPermission('roles_view'))
                        <li class="sidebar-submenu-container">
                            <a href="#adminSubmenu" class="sidebar-submenu-toggle" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                <i class="bi bi-shield-check"></i>
                                <span>Roles & Permissions</span>
                                <i class="bi bi-chevron-down sidebar-submenu-chevron"></i>
                            </a>
                            <ul class="sidebar-submenu collapse" id="adminSubmenu">
                                <li>
                                    <a href="{{ route('admin.roles.index') }}" class="{{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
                                        <i class="bi bi-shield-check"></i>
                                        <span>Roles</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.role-permissions.index') }}" class="{{ request()->routeIs('admin.role-permissions.*') ? 'active' : '' }}">
                                        <i class="bi bi-shield-lock"></i>
                                        <span>Role Permissions</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endif

                    @if(Auth::user()->hasPermission('activity_logs_view'))
                        <li>
                            <a href="{{ route('admin.activity-logs.index') }}" class="{{ request()->routeIs('admin.activity-logs.*') ? 'active' : '' }}">
                                <i class="bi bi-clock-history"></i>
                                <span>Activity Logs</span>
                            </a>
                        </li>
                    @endif
                @endif

                <!-- Location Management (Admin Only) -->
                @if(Auth::user()->hasAnyPermission(['countries_view', 'states_view', 'districts_view', 'talukas_view', 'villages_view']))
                    <li class="sidebar-menu-header">Location Management</li>
                    <li class="sidebar-submenu-container">
                        <a href="#locationSubmenu" class="sidebar-submenu-toggle" data-bs-toggle="collapse" role="button" aria-expanded="false">
                            <i class="bi bi-map"></i>
                            <span>Locations</span>
                            <i class="bi bi-chevron-down sidebar-submenu-chevron"></i>
                        </a>
                        <ul class="sidebar-submenu collapse" id="locationSubmenu">
                            @if(Auth::user()->hasPermission('countries_view'))
                                <li>
                                    <a href="{{ route('admin.countries.index') }}" class="{{ request()->routeIs('admin.countries.*') ? 'active' : '' }}">
                                        <i class="bi bi-globe"></i>
                                        <span>Countries</span>
                                    </a>
                                </li>
                            @endif

                            @if(Auth::user()->hasPermission('states_view'))
                                <li>
                                    <a href="{{ route('admin.states.index') }}" class="{{ request()->routeIs('admin.states.*') ? 'active' : '' }}">
                                        <i class="bi bi-map"></i>
                                        <span>States</span>
                                    </a>
                                </li>
                            @endif

                            @if(Auth::user()->hasPermission('districts_view'))
                                <li>
                                    <a href="{{ route('admin.districts.index') }}" class="{{ request()->routeIs('admin.districts.*') ? 'active' : '' }}">
                                        <i class="bi bi-pin-map"></i>
                                        <span>Districts</span>
                                    </a>
                                </li>
                            @endif

                            @if(Auth::user()->hasPermission('talukas_view'))
                                <li>
                                    <a href="{{ route('admin.talukas.index') }}" class="{{ request()->routeIs('admin.talukas.*') ? 'active' : '' }}">
                                        <i class="bi bi-geo-alt"></i>
                                        <span>Talukas</span>
                                    </a>
                                </li>
                            @endif

                            @if(Auth::user()->hasPermission('villages_view'))
                                <li>
                                    <a href="{{ route('admin.villages.index') }}" class="{{ request()->routeIs('admin.villages.*') ? 'active' : '' }}">
                                        <i class="bi bi-houses"></i>
                                        <span>Villages</span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif

                <!-- Patient Management (Admin + Data Entry) -->
                @if(Auth::user()->hasPermission('patients_view'))
                    <li class="sidebar-menu-header">Patient Management</li>
                    <li class="sidebar-submenu-container">
                        <a href="#patientSubmenu" class="sidebar-submenu-toggle" data-bs-toggle="collapse" role="button" aria-expanded="false">
                            <i class="bi bi-heart-pulse"></i>
                            <span>Patients</span>
                            <i class="bi bi-chevron-down sidebar-submenu-chevron"></i>
                        </a>
                        <ul class="sidebar-submenu collapse" id="patientSubmenu">
                            <li>
                                <a href="{{ route('admin.patients.index') }}" class="{{ request()->routeIs('admin.patients.index') || request()->routeIs('admin.patients.show') || request()->routeIs('admin.patients.edit') || request()->routeIs('admin.patients.create') ? 'active' : '' }}">
                                    <i class="bi bi-list-ul"></i>
                                    <span>View All Patients</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.patients.create') }}" class="{{ request()->routeIs('admin.patients.create') ? 'active' : '' }}">
                                    <i class="bi bi-person-plus-fill"></i>
                                    <span>Add Patient</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.patients.import-form') }}" class="{{ request()->routeIs('admin.patients.import-form') || request()->routeIs('admin.patients.import') ? 'active' : '' }}">
                                    <i class="bi bi-upload"></i>
                                    <span>Bulk Import</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif

                <!-- Campaign Management (Admin Only) -->
                <li class="sidebar-menu-header">Campaign Management</li>
                <li>
                    <a href="{{ route('admin.campaign-types.index') }}" class="{{ request()->routeIs('admin.campaign-types.*') ? 'active' : '' }}">
                        <i class="bi bi-collection"></i>
                        <span>Campaign Types</span>
                    </a>
                </li>

                <!-- Analytics & Reports -->
                @if(Auth::user()->hasPermission('analytics_view'))
                    <li class="sidebar-menu-header">Analytics & Reports</li>
                    <li class="sidebar-submenu-container">
                        <a href="#analyticsSubmenu" class="sidebar-submenu-toggle" data-bs-toggle="collapse" role="button" aria-expanded="false">
                            <i class="bi bi-graph-up-arrow"></i>
                            <span>Analytics</span>
                            <i class="bi bi-chevron-down sidebar-submenu-chevron"></i>
                        </a>
                        <ul class="sidebar-submenu collapse" id="analyticsSubmenu">
                            <li>
                                <a href="{{ route('admin.analytics.index') }}" class="{{ request()->routeIs('admin.analytics.*') ? 'active' : '' }}">
                                    <i class="bi bi-bar-chart-line"></i>
                                    <span>Health Dashboard</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif

                <!-- Complaints Management -->
                @if(Auth::user()->hasPermission('complaints_view'))
                    <li class="sidebar-menu-header">Complaints</li>
                    <li>
                        <a href="{{ route('admin.complaints.index') }}" class="{{ request()->routeIs('admin.complaints.*') ? 'active' : '' }}">
                            <i class="bi bi-exclamation-triangle"></i><span> Complaints</span>
                        </a>
                    </li>
                @endif

                <!-- Diagnosis Management -->
                @if(Auth::user()->hasPermission('diagnoses_view'))
                    <li class="sidebar-menu-header">Diagnosis</li>
                    <li>
                        <a href="{{ route('admin.diagnoses.index') }}"
                        class="{{ request()->routeIs('admin.diagnoses.*') ? 'active' : '' }}">
                            <i class="bi bi-clipboard2-pulse"></i>
                            <span> Diagnosis</span>
                        </a>
                    </li>
                @endif

                <!-- Known Conditions Management -->
                @if(Auth::user()->hasPermission('known_conditions_view'))
                    <li class="sidebar-menu-header">Known Conditions</li>
                    <li>
                        <a href="{{ route('admin.known-conditions.index') }}" class="{{ request()->routeIs('admin.known-conditions.*') ? 'active' : '' }}">
                            <i class="bi bi-virus2"></i>
                            <span>Known Conditions</span>
                        </a>
                    </li>
                @endif
            </ul>

            <div class="sidebar-footer">  
                <div class="user-info">
                    <div class="user-avatar">
                        {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                    </div>
                    <div class="user-details">
                        <h4>{{ Auth::user()->name }}</h4>
                        <p>{{ Auth::user()->isAdmin() ? 'Administrator' : 'User' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="main-content">
            <!-- Top Navigation Bar -->
            <div class="topbar">
                <div class="topbar-left">
                    <button class="menu-toggle">
                        <i class="bi bi-list"></i>
                    </button>
                    <h1>@yield('page-title', 'Patient Management System')</h1>
                </div>
                <div class="topbar-right">
                    <div class="notification-icon">
                        <i class="bi bi-bell"></i>
                        <span class="notification-badge">0</span>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger btn-sm">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </button>
                    </form>
                </div>
            </div>

            <!-- Content Area with Flash Messages -->
            <div class="content-area">
                <!-- Flash Messages -->
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong><i class="bi bi-exclamation-circle"></i> Error!</strong>
                        @if (count($errors) === 1)
                            {{ $errors->first() }}
                        @else
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Page Content -->
                @yield('admin-content')
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('assets/js/admin.js') }}"></script>

    @stack('scripts')
</body>
</html>
