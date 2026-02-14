@extends('admin.layouts.master')
@section('users', 'active')
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
                    <h2 class="page-content-title fw-semibold fs-5">All Users</h2>
                    <p class="page-subtitle">Manage User with wallet ballance and update status</p>
                </div>
                <div class="d-flex align-items-center gap-2">
                     <!-- Export Button -->
                    <button class="btn btn-light text-dark border">
                        <i class="fa-solid fa-download me-1"></i> Export
                    </button>

                    <!-- Add User Button -->
                    <button class="btn btn-primary-custom"
                            data-bs-toggle="modal"
                            data-bs-target="#addUserModal">
                        <i class="fa-solid fa-plus me-1"></i> Add User
                    </button>
                </div>
            </div>
        </div>

        <!-- Main Card -->
        <div class="main-card">
            <!-- Card Header -->
            <div
                class="card-header d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3">
                <h5 class="card-title">User Overview</h5>
            </div>

            <!-- Table -->
            <div class="table-container">
                <table id="usersTable" class="data-table table table-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th>User ID</th>
                            <th>Full Name</th>
                            {{-- <th>Country</th> --}}
                            <th>Email/Phone</th>
                            <th>Wallet Balance</th>
                            <th>Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                     <tbody>
        <tr>
            <td>#U1001</td>
            <td>John Doe</td>
            <td>
                <div>john@example.com</div>
                <small class="text-muted">+880 1711-000001</small>
            </td>
            <td>$250.00</td>
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
            <td>#U1002</td>
            <td>Sarah Ahmed</td>
            <td>
                <div>sarah@mail.com</div>
                <small class="text-muted">+880 1811-000002</small>
            </td>
            <td>$120.50</td>
            <td>
                <span class="badge bg-warning text-dark">Pending</span>
            </td>
            <td class="text-center">
                <button class="btn btn-sm btn-info">View</button>
                <button class="btn btn-sm btn-warning">Edit</button>
                <button class="btn btn-sm btn-danger">Suspend</button>
            </td>
        </tr>

        <tr>
            <td>#U1003</td>
            <td>Michael Smith</td>
            <td>
                <div>michael@gmail.com</div>
                <small class="text-muted">+880 1911-000003</small>
            </td>
            <td>$0.00</td>
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
            <td>#U1004</td>
            <td>Emma Watson</td>
            <td>
                <div>emma@yahoo.com</div>
                <small class="text-muted">+880 1611-000004</small>
            </td>
            <td>$980.75</td>
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
