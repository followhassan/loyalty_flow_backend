@extends('admin.layouts.master')
@section('promotions', 'active')
@section('title') Promotions @endsection

@section('content')
    <main class="container-fluid p-3 p-lg-4">

        <!-- Page Header -->
        <div class="page-header">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                <div class="mb-3 mb-md-0">
                    <h2 class="page-content-title fw-semibold fs-5">Promotions</h2>
                    <p class="page-subtitle mb-0">Manage discount campaigns and promotional offers</p>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <button class="btn btn-primary-custom" data-bs-toggle="modal" data-bs-target="#addPromotionModal">
                        <i class="fa-solid fa-plus me-1"></i> Add Promotion
                    </button>
                </div>
            </div>
        </div>

        <!-- Main Card -->
        <div class="main-card">
            <div class="card-header d-flex justify-content-between align-items-center mb-3">
                <h5 class="card-title mb-0">Promotion Overview</h5>
            </div>

            <div class="table-container">
                <table id="promotionTable" class="data-table table table-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th>Promotion Name</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Discount</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Uses</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>New Year Offer</td>
                            <td><span class="badge bg-info">Percentage</span></td>
                            <td><span class="badge bg-success">Active</span></td>
                            <td>20%</td>
                            <td>01 Jan 2026</td>
                            <td>15 Jan 2026</td>
                            <td>120 / 500</td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-info">View</button>
                                <button class="btn btn-sm btn-warning">Edit</button>
                                {{-- <button class="btn btn-sm btn-danger">Deactivate</button> --}}
                            </td>
                        </tr>

                        <tr>
                            <td>Summer Cashback</td>
                            <td><span class="badge bg-primary">Fixed</span></td>
                            <td><span class="badge bg-warning text-dark">Scheduled</span></td>
                            <td>$15</td>
                            <td>01 Mar 2026</td>
                            <td>31 Mar 2026</td>
                            <td>0 / 300</td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-info">View</button>
                                <button class="btn btn-sm btn-warning">Edit</button>
                                {{-- <button class="btn btn-sm btn-danger">Cancel</button> --}}
                            </td>
                        </tr>

                        <tr>
                            <td>Black Friday Deal</td>
                            <td><span class="badge bg-info">Percentage</span></td>
                            <td><span class="badge bg-danger">Expired</span></td>
                            <td>40%</td>
                            <td>25 Nov 2025</td>
                            <td>30 Nov 2025</td>
                            <td>480 / 500</td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-info">View</button>
                                <button class="btn btn-sm btn-warning">Edit</button>
                                {{-- <button class="btn btn-sm btn-secondary">Duplicate</button> --}}
                            </td>
                        </tr>

                        <tr>
                            <td>Referral Bonus</td>
                            <td><span class="badge bg-primary">Fixed</span></td>
                            <td><span class="badge bg-success">Active</span></td>
                            <td>$10</td>
                            <td>01 Feb 2026</td>
                            <td>30 Apr 2026</td>
                            <td>75 / Unlimited</td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-info">View</button>
                                <button class="btn btn-sm btn-warning">Edit</button>
                                {{-- <button class="btn btn-sm btn-danger">Deactivate</button> --}}
                            </td>
                        </tr>

                        <tr>
                            <td>Flash Sale 7.7</td>
                            <td><span class="badge bg-info">Percentage</span></td>
                            <td><span class="badge bg-success">Active</span></td>
                            <td>15%</td>
                            <td>07 Jul 2026</td>
                            <td>07 Jul 2026</td>
                            <td>210 / 300</td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-info">View</button>
                                <button class="btn btn-sm btn-warning">Edit</button>
                                {{-- <button class="btn btn-sm btn-danger">Deactivate</button> --}}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </main>
@endsection
