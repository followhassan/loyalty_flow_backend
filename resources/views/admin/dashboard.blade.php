@extends('admin.layouts.master')

@push('style')
    <style>
        /* Stats Cards */
        .stats-card:hover {
            transform: translateY(-2px);
        }

        .stats-icon-container {
            width: 64px;
            height: 64px;
            border-radius: 16px;
        }

        .stats-card:hover .stats-icon-container {
            transform: scale(1.1);
        }

        /* Chart Cards */
        .chart-card {
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        /* Metric Cards */
        .metric-card {
            background: linear-gradient(135deg, rgba(26, 115, 232, 0.1), white);
            border-radius: 12px;
            border: 2px solid rgba(26, 115, 232, 0.2);
            padding: 20px;
            height: 100%;
        }

        /* Activity Items */
        .activity-item {
            display: flex;
            align-items: flex-start;
            gap: 16px;
            padding: 20px;
            border-bottom: 1px solid #E5E7EB;
            transition: background-color 0.2s ease;
        }

        .activity-item:last-child {
            border-bottom: none;
            margin-bottom: 0 !important;
        }

        .activity-item:hover {
            background-color: var(--light-gray);
        }

        .activity-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            flex-shrink: 0;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .tenant-badge {
            background: white;
            border: 1px solid #E5E7EB;
            padding: 4px 10px;
            border-radius: 6px;
        }

        div.dataTables_wrapper div.dataTables_paginate ul.pagination {
            margin: 0;
        }

        div.table-responsive>div.dataTables_wrapper>div.row {
            align-items: center;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .stats-value {
                font-size: 24px;
            }

            .stats-icon-container {
                width: 48px;
                height: 48px;
            }

            .dashboard-header {
                padding: 20px !important;
            }

            .chart-container {
                height: 200px;
            }

            div.dataTables_wrapper div.dataTables_length {
                margin-bottom: 0;
                padding-bottom: 0;
            }
        }

        /* DataTables customization */
        .dataTables_wrapper {
            padding: 0;
        }

        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter {
            padding: 20px;
            margin: 0;
        }

        .dataTables_wrapper .dataTables_info,
        .dataTables_wrapper .dataTables_paginate {
            padding: 20px;
            margin: 0;
        }

        /* Scrollable table container */
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .table-responsive table {
            min-width: 600px;
        }
    </style>
@endpush

@section('dashboard', 'active')
@section('title') {{ $data['title'] ?? '' }} @endsection

@section('content')
    <!-- Main Content Area -->
    <main class="container-fluid p-3 p-lg-4">
        <!-- Dashboard Header -->
        <div class="dashboard-header p-4 p-lg-5 mb-4 position-relative">
            <div class="position-relative z-1">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <div
                            class="d-inline-flex align-items-center gap-2 bg-light bg-opacity-10 px-3 py-2 rounded-pill mb-3">
                            <i class="fas fa-bolt"></i>
                            <span class="fw-semibold">Admin</span>
                        </div>
                        <h1 class="display-6 fw-bold mb-2">System Overview</h1>
                        <p class="lead opacity-90 mb-0">Monitoring active users •
                           </p>
                    </div>
                    {{-- <div class="col-lg-4 mt-3 mt-lg-0 text-lg-end">
                        <div class="system-status d-inline-flex mb-2">
                            <div class="status-indicator"></div>
                            <span class="fw-semibold">All Systems Operational</span>
                        </div>
                        <p class="mb-0 opacity-70">99.8% Uptime</p>
                    </div> --}}
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row g-3 mb-3">
            <div class="col-md-6 col-lg-3">
                <div class="stats-card p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <p class="text-uppercase text-muted small fw-semibold mb-2">Total Users</p>
                            <p class="stats-value mb-3">
                                10
                            </p>
                        </div>
                        <div class="stats-icon-container" style="background-color: rgba(26, 115, 232, 0.1);">
                            <i class="fas fa-building fa-2x text-primary" style="color: var(--primary-blue);"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="stats-card p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <p class="text-uppercase text-muted small fw-semibold mb-2">Monthly Revenue</p>
                            <p class="stats-value mb-3">
                                $ 10</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="stats-card p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <p class="text-uppercase text-muted small fw-semibold mb-2">Documents Processed</p>
                            <p class="stats-value mb-3">15</p>
                        </div>
                        <div class="stats-icon-container" style="background-color: rgba(220, 252, 231, 1);">
                            <i class="fas fa-file-text fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="stats-card p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <p class="text-uppercase text-muted small fw-semibold mb-2">Generated Reports</p>
                            <p class="stats-value mb-3">15</p>
                        </div>
                        <div class="stats-icon-container" style="background-color: rgba(243, 232, 255, 1);">
                            <i class="fas fa-download fa-2x text-purple"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row g-3 mb-3">
            <div class="col-lg-6">
                <div class="chart-card">
                    <div class="p-4 border-bottom">
                        <h5 class="mb-1">Monthly Revenue</h5>
                        <p class="text-muted mb-0 small">Last 6 months</p>
                    </div>
                    <div class="p-4">
                        <div class="chart-container">
                            <canvas id="revenueChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="chart-card">
                    <div class="p-4 border-bottom">
                        <h5 class="mb-1">Document Processing</h5>
                        <p class="text-muted mb-0 small">Last 7 days</p>
                    </div>
                    <div class="p-4">
                        <div class="chart-container">
                            <canvas id="documentsChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row quick-actions mb-2">
            <div class="col-12">
                <h2 class="h4 fw-bold mb-2">Quick Actions</h2>
            </div>
        </div>
    </main>
@endsection

@push('script')
    {{-- <script>
        const documentChartLabels = @json($data['documentChartLabels']);
        const documentChartData = @json($data['documentChartData']);
        const revenueChartLabels = @json($data['revenueChartLabels']);
        const revenueChartData = @json($data['revenueChartData']);
        $(document).ready(function() {
            // Initialize Charts
            initializeCharts();
        });

        function initializeCharts() {
            // Revenue Chart (Bar Chart)
            const revenueCtx = document.getElementById('revenueChart').getContext('2d');
            const revenueChart = new Chart(revenueCtx, {
                type: 'bar',
                data: {
                    labels: revenueChartLabels,
                    datasets: [{
                        label: 'Monthly Revenue ({{ getDefaultCurrencySymbol() }})',
                        data: revenueChartData,
                        backgroundColor: 'rgba(26, 115, 232, 0.7)',
                        borderColor: 'rgba(26, 115, 232, 1)',
                        borderWidth: 1,
                        borderRadius: 0,
                        borderSkipped: false,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(255, 255, 255, 0.9)',
                            titleColor: '#0A2A43',
                            bodyColor: '#606F7B',
                            borderColor: '#dee2e6',
                            borderWidth: 1,
                            cornerRadius: 8,
                            padding: 12,
                            displayColors: false,
                            callbacks: {
                                label: function(context) {
                                    return `{{ getDefaultCurrencySymbol() }}${context.parsed.y.toLocaleString()}`;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)',
                                drawBorder: false
                            },
                            ticks: {
                                color: '#606F7B',
                                callback: function(value) {
                                    return '{{ getDefaultCurrencySymbol() }}' + value.toLocaleString();
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: '#606F7B'
                            }
                        }
                    }
                }
            });

            // Documents Chart (Line Chart)
            const documentChartCtx = document.getElementById('documentsChart').getContext('2d');
            const documentChartInstance = new Chart(documentChartCtx, {
                type: 'line',
                data: {
                    labels: documentChartLabels,
                    datasets: [{
                        label: 'Documents Processed',
                        data: documentChartData,
                        backgroundColor: 'rgba(232, 182, 0, 0.1)',
                        borderColor: 'rgba(232, 182, 0, 1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: 'rgba(232, 182, 0, 1)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 7
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(255, 255, 255, 0.9)',
                            titleColor: '#0A2A43',
                            bodyColor: '#606F7B',
                            borderColor: '#dee2e6',
                            borderWidth: 1,
                            cornerRadius: 8,
                            padding: 12,
                            displayColors: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)',
                                drawBorder: false
                            },
                            ticks: {
                                color: '#606F7B',
                                stepSize: 20
                            }
                        },
                        x: {
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)',
                                drawBorder: false
                            },
                            ticks: {
                                color: '#606F7B'
                            }
                        }
                    }
                }
            });

            // Handle window resize for charts
            $(window).on('resize', function() {
                revenueChart.resize();
                documentsChart.resize();
            });
        }
    </script> --}}
@endpush
