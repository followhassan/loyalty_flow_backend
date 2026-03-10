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
                    <button class="btn btn-primary-custom" data-bs-toggle="modal" data-bs-target="#addUserModal">
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
                        @foreach ($users as $item)
                            <tr>
                                <td>#{{ $item->user_id }}</td>
                                <td>{{ $item->name }}</td>
                                <td>
                                    <div>{{ $item->email }}</div>
                                    <small class="text-muted">{{ $item->phone }}</small>
                                </td>
                                <td>$ 0</td>
                                <td>
                                    @if ($item->status == 1)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-info viewBtn" data-id="{{ $item->id }}"
                                        data-userid="{{ $item->user_id }}" data-name="{{ $item->name }}"
                                        data-email="{{ $item->email }}" data-phone="{{ $item->phone }}"
                                        data-status="{{ $item->status }}" data-bs-toggle="modal"
                                        data-bs-target="#viewUserModal">
                                        View
                                    </button>

                                    <button class="btn btn-sm btn-warning editBtn" data-id="{{ $item->id }}"
                                        data-name="{{ $item->name }}" data-email="{{ $item->email }}"
                                        data-phone="{{ $item->phone }}" data-status="{{ $item->status }}"
                                        data-bs-toggle="modal" data-bs-target="#editUserModal">
                                        Edit
                                    </button>
                                    {{-- <button class="btn btn-sm btn-danger">Suspend</button> --}}
                                </td>
                            </tr>
                        @endforeach

                        {{-- <tr>
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
                        </tr> --}}
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <div class="modal fade" id="viewUserModal">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">User Details</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <p><strong>User ID:</strong> <span id="view_user_id"></span></p>
                    <p><strong>Name:</strong> <span id="view_name"></span></p>
                    <p><strong>Email:</strong> <span id="view_email"></span></p>
                    <p><strong>Phone:</strong> <span id="view_phone"></span></p>
                    <p><strong>Status:</strong> <span id="view_status"></span></p>

                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="editUserModal">
        <div class="modal-dialog">
            <div class="modal-content">


                <div class="modal-header">
                    <h5 class="modal-title">Edit User</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <form method="POST" action="{{ route('admin.users.update') }}">
                    @csrf
                    <div class="modal-body">

                        <input type="hidden" name="id" id="edit_id">

                        <div class="mb-3">
                            <label>Name</label>
                            <input type="text" name="name" id="edit_name" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label>Email</label>
                            <input type="email" name="email" id="edit_email" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label>Phone</label>
                            <input type="text" name="phone" id="edit_phone" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label>Status</label>
                            <select name="status" id="edit_status" class="form-control">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-success">Update</button>
                    </div>

                </form>

            </div>
        </div>
    </div>




@endsection


@push('script')
    <script>
        $(document).ready(function() {

            $('.viewBtn').on('click', function() {

                $('#view_user_id').text($(this).data('userid'));
                $('#view_name').text($(this).data('name'));
                $('#view_email').text($(this).data('email'));
                $('#view_phone').text($(this).data('phone'));

                let status = $(this).data('status') == 1 ? 'Active' : 'Inactive';
                $('#view_status').text(status);

            });


            $('.editBtn').on('click', function() {

                $('#edit_id').val($(this).data('id'));
                $('#edit_name').val($(this).data('name'));
                $('#edit_email').val($(this).data('email'));
                $('#edit_phone').val($(this).data('phone'));
                $('#edit_status').val($(this).data('status'));

            });

        });
    </script>
@endpush
