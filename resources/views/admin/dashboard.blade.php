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

        .activity-list {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .activity-item {
            position: relative;
        }

        .activity-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 16px;
        }

        .chart-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }
    </style>
@endpush

@section('dashboard', 'active')
@section('title') {{ $data['title'] ?? '' }} @endsection

@section('content')
    <!-- Main Content Area -->
    <main class="container-fluid p-3 p-lg-4">
        <!-- Dashboard Header -->

        <!-- Stats Cards -->
        <div class="row g-3 mb-3">
            <div class="col-md-6 col-lg-3">
                <div class="stats-card p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <p class="text-uppercase text-muted small fw-semibold mb-2">
                                Total Revenue
                            </p>

                            <p class="stats-value mb-1">
                                $10,000
                            </p>

                            <!-- Trend -->
                            <p class="mb-0 small text-success fw-semibold">
                                <i class="fa-solid fa-arrow-trend-up me-1"></i>
                                12.5% increase
                            </p>
                            <small class="text-mute">vs last month</small>
                        </div>

                        <div class="stats-icon-container" style="background-color: rgba(26, 115, 232, 0.1);">
                            <i class="fa-solid fa-dollar-sign fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-md-6 col-lg-3">
                <div class="stats-card p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <p class="text-uppercase text-muted small fw-semibold mb-2">
                                Active User
                            </p>

                            <p class="stats-value mb-1">
                                1045
                            </p>

                            <!-- Trend -->
                            <p class="mb-0 small text-success fw-semibold">
                                <i class="fa-solid fa-arrow-trend-up me-1"></i>
                                8.2% increase
                            </p>
                            <small class="text-mute">vs last month</small>
                        </div>

                        <div class="stats-icon-container" style="background-color: rgba(26, 115, 232, 0.1);">
                            <i class="fa-solid fa-user-group fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="stats-card p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <p class="text-uppercase text-muted small fw-semibold mb-2">
                                Total Order
                            </p>

                            <p class="stats-value mb-1">
                                1154
                            </p>

                            <!-- Trend -->
                            <p class="mb-0 small text-success fw-semibold">
                                <i class="fa-solid fa-arrow-trend-down me-1"></i>
                                8.2% increase
                            </p>
                            <small class="text-mute">vs last month</small>
                        </div>

                        <div class="stats-icon-container" style="background-color: rgba(26, 115, 232, 0.1);">
                            <i class="fa-solid fa-bag-shopping fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="stats-card p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <p class="text-uppercase text-muted small fw-semibold mb-2">
                                Growth Rate
                            </p>

                            <p class="stats-value mb-1">
                                28%
                            </p>

                            <!-- Trend -->
                            <p class="mb-0 small text-success fw-semibold">
                                <i class="fa-solid fa-arrow-trend-up me-1"></i>
                                2.8% increase
                            </p>
                            <small class="text-mute">vs last month</small>
                        </div>

                        <div class="stats-icon-container" style="background-color: rgba(26, 115, 232, 0.1);">
                            <i class="fa-solid fa-arrow-trend-up fa-2x"></i>
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
                        <h5 class="mb-1">User Growth</h5>
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
                        <h5 class="mb-1">Recent Activity</h5>
                        <p class="text-muted mb-0 small">Last 7 days</p>
                    </div>

                    <div class="p-4">
                        <ul class="activity-list list-unstyled mb-0">

                            <li class="activity-item d-flex align-items-start">
                                <div class="activity-icon bg-success">
                                    <i class="fa-solid fa-file-circle-check"></i>
                                </div>
                                <div class="ms-3">
                                    <p class="mb-1 fw-semibold">Document Approved</p>
                                    <p class="mb-0 small text-muted">
                                        Invoice_1023.pdf was approved
                                    </p>
                                    <small class="text-muted">2 hours ago</small>
                                </div>
                            </li>

                            <li class="activity-item d-flex align-items-start">
                                <div class="activity-icon bg-primary">
                                    <i class="fa-solid fa-file-arrow-up"></i>
                                </div>
                                <div class="ms-3">
                                    <p class="mb-1 fw-semibold">New Document Uploaded</p>
                                    <p class="mb-0 small text-muted">
                                        Contract_Agreement.docx uploaded
                                    </p>
                                    <small class="text-muted">5 hours ago</small>
                                </div>
                            </li>

                            <li class="activity-item d-flex align-items-start">
                                <div class="activity-icon bg-warning">
                                    <i class="fa-solid fa-file-pen"></i>
                                </div>
                                <div class="ms-3">
                                    <p class="mb-1 fw-semibold">Document Updated</p>
                                    <p class="mb-0 small text-muted">
                                        NDA_Form.pdf updated
                                    </p>
                                    <small class="text-muted">Yesterday</small>
                                </div>
                            </li>

                            <li class="activity-item d-flex align-items-start">
                                <div class="activity-icon bg-danger">
                                    <i class="fa-solid fa-file-circle-xmark"></i>
                                </div>
                                <div class="ms-3">
                                    <p class="mb-1 fw-semibold">Document Rejected</p>
                                    <p class="mb-0 small text-muted">
                                        Invoice_0987.pdf was rejected
                                    </p>
                                    <small class="text-muted">2 days ago</small>
                                </div>
                            </li>

                        </ul>
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
    <script>
        const ctx = document.getElementById('revenueChart').getContext('2d');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Sep', 'Oct', 'Nov', 'Dec', 'Jan', 'Feb'],
                datasets: [{
                    label: 'Revenue ($)',
                    data: [6500, 7200, 8100, 9000, 8700, 10000],
                    backgroundColor: '#006a6c',
                    borderRadius: 6,
                    barThickness: 35
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
                        callbacks: {
                            label: function(context) {
                                return '$' + context.parsed.y.toLocaleString();
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '$' + value;
                            }
                        },
                        grid: {
                            color: 'rgba(0,0,0,0.05)'
                        }
                    }
                }
            }
        });
    </script>
@endpush
