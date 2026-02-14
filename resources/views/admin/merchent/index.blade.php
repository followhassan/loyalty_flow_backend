@extends('admin.layouts.master')
@section('merchants', 'active')
@section('title'){{ $title ?? '' }} @endsection

@push('style')
    <style>
        .swal-high-zindex {
            z-index: 99999 !important;
        }

        /* Loader wrapper positioned over button */
    </style>
@endpush

@section('content')
    <main class="container-fluid p-3 p-lg-4">
        <!-- Page Header -->
        <div class="page-header">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                <div class="mb-3 mb-md-0 w">
                    <h2 class="page-content-title fw-semibold fs-5">Merchents</h2>
                    <p class="page-subtitle">Manage and monitor all register merchent account</p>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <button class="btn btn-primary-custom" data-bs-toggle="modal" data-bs-target="#addUserModal">
                        <i class="fa-solid fa-plus me-1"></i> Export
                    </button>
                </div>
            </div>
        </div>

        <!-- Main Card -->
        <div class="main-card">
            <!-- Card Header -->
            <div
                class="card-header d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3">
                <h5 class="card-title">Merchents Overview</h5>
            </div>

            <!-- Table -->
            <div class="table-container">
                <table id="usersTable" class="data-table table table-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th>Merchents ID</th>
                            <th>Business Name</th>
                            {{-- <th>Country</th> --}}
                            <th>City</th>
                            <th>Total Transcations</th>
                            <th>Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>#M1001</td>
                            <td>Dhaka Electronics</td>
                            <td>Dhaka</td>
                            <td>1,245</td>
                            <td><span class="badge bg-success">Active</span></td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-info">View</button>
                                <button class="btn btn-sm btn-warning">Edit</button>
                                <button class="btn btn-sm btn-danger">Suspend</button>
                            </td>
                        </tr>

                        <tr>
                            <td>#M1002</td>
                            <td>Chittagong Fashion House</td>
                            <td>Chittagong</td>
                            <td>865</td>
                            <td><span class="badge bg-warning text-dark">Pending</span></td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-info">View</button>
                                <button class="btn btn-sm btn-warning">Edit</button>
                                <button class="btn btn-sm btn-success">Approve</button>
                            </td>
                        </tr>

                        <tr>
                            <td>#M1003</td>
                            <td>Sylhet Grocery Mart</td>
                            <td>Sylhet</td>
                            <td>432</td>
                            <td><span class="badge bg-danger">Suspended</span></td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-info">View</button>
                                <button class="btn btn-sm btn-warning">Edit</button>
                                <button class="btn btn-sm btn-success">Activate</button>
                            </td>
                        </tr>

                        <tr>
                            <td>#M1004</td>
                            <td>Rajshahi Mobile Zone</td>
                            <td>Rajshahi</td>
                            <td>2,103</td>
                            <td><span class="badge bg-success">Active</span></td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-info">View</button>
                                <button class="btn btn-sm btn-warning">Edit</button>
                                <button class="btn btn-sm btn-danger">Suspend</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>






@endsection


@push('script')
@endpush
