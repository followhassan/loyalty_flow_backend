@extends('admin.layouts.master')
@section('agents', 'active')
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
                    <h2 class="page-content-title fw-semibold fs-5">Agents</h2>
                    {{-- <p class="page-subtitle">Manage your SaaS users and subscriptions</p> --}}
                </div>
                <div class="d-flex align-items-center gap-2">
                    <button class="btn btn-primary-custom"
                            data-bs-toggle="modal"
                            data-bs-target="#addUserModal">
                        <i class="fa-solid fa-plus me-1"></i> Add Agent
                    </button>
                </div>
            </div>
        </div>

        <!-- Main Card -->
        <div class="main-card">
            <!-- Card Header -->
            <div
                class="card-header d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3">
                <h5 class="card-title">Agent Overview</h5>
            </div>

            <!-- Table -->
            <div class="table-container">
                <table id="usersTable" class="data-table table table-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th>Agent ID</th>
                            <th>Agent Name</th>
                            {{-- <th>Country</th> --}}
                            <th>Total Companies</th>
                            <th>Commission Earnd</th>
                            <th>Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
        <tr>
            <td>#A1001</td>
            <td>Rahim Uddin</td>
            <td>12</td>
            <td>$3,450.00</td>
            <td>
                <span class="badge bg-success">Active</span>
            </td>
            <td class="text-center">
                <button class="btn btn-sm btn-info">View</button>
                <button class="btn btn-sm btn-warning">Edit</button>
                <button class="btn btn-sm btn-danger">Suspend</button>
            </td>
        </tr>

        <tr>
            <td>#A1002</td>
            <td>Tanvir Hasan</td>
            <td>8</td>
            <td>$1,980.50</td>
            <td>
                <span class="badge bg-warning text-dark">Pending</span>
            </td>
            <td class="text-center">
                <button class="btn btn-sm btn-info">View</button>
                <button class="btn btn-sm btn-warning">Edit</button>
                <button class="btn btn-sm btn-success">Approve</button>
            </td>
        </tr>

        <tr>
            <td>#A1003</td>
            <td>Farhana Akter</td>
            <td>15</td>
            <td>$5,720.00</td>
            <td>
                <span class="badge bg-success">Active</span>
            </td>
            <td class="text-center">
                <button class="btn btn-sm btn-info">View</button>
                <button class="btn btn-sm btn-warning">Edit</button>
                <button class="btn btn-sm btn-danger">Suspend</button>
            </td>
        </tr>

        <tr>
            <td>#A1004</td>
            <td>Mehedi Hassan</td>
            <td>4</td>
            <td>$640.00</td>
            <td>
                <span class="badge bg-danger">Blocked</span>
            </td>
            <td class="text-center">
                <button class="btn btn-sm btn-info">View</button>
                <button class="btn btn-sm btn-warning">Edit</button>
                <button class="btn btn-sm btn-success">Activate</button>
            </td>
        </tr>

        <tr>
            <td>#A1005</td>
            <td>Nusrat Jahan</td>
            <td>10</td>
            <td>$2,310.75</td>
            <td>
                <span class="badge bg-success">Active</span>
            </td>
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
