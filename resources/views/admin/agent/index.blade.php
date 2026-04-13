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
                    @can('view agents')
                        <button class="btn btn-primary-custom" data-bs-toggle="modal" data-bs-target="#addAgentModal">
                            <i class="fa-solid fa-plus me-1"></i> Add Agent
                        </button>
                    @endcan
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
                            <th>Total Merchants</th>
                            {{-- <th>Commission Earnd</th> --}}
                            <th>Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($agents as $agent)
                            <tr>
                                <td>#{{ $agent->user_id }}</td>
                                <td>{{ $agent->name }}</td>
                                <td>{{ $agent->merchants_count }}</td>
                                {{-- <td>$3,450.00</td> --}}
                                <td>
                                    @if ($agent->status == 1)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            Actions
                                        </button>

                                        <ul class="dropdown-menu">

                                            {{-- View --}}
                                            @can('view agents')
                                                <li>
                                                    <button class="dropdown-item viewBtn"
                                                        data-id="{{ $agent->id }}"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#viewModal">
                                                        View
                                                    </button>
                                                </li>
                                            @endcan

                                            {{-- Edit --}}
                                            @can('update agent')
                                                <li>
                                                    <button class="dropdown-item editBtn"
                                                        data-id="{{ $agent->id }}"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#editModal">
                                                        Edit
                                                    </button>
                                                </li>
                                            @endcan

                                            {{-- 🔥 Agent Transactions --}}
                                            @can('view agent merchants')
                                                <li>
                                                    <a href="{{ route('admin.agents.merchantList', $agent->id) }}" class="dropdown-item">
                                                        Merchants
                                                    </a>
                                                </li>
                                            @endcan

                                            @can('update agent')
                                                <li><hr class="dropdown-divider"></li>

                                                {{-- Status Toggle --}}
                                                @if($agent->status == 1)
                                                    <li>
                                                        <a href="{{ route('admin.agents.toggleStatus', $agent->id) }}"
                                                        class="dropdown-item text-danger"
                                                        onclick="return confirm('Suspend this agent?')">
                                                            Suspend
                                                        </a>
                                                    </li>
                                                @else
                                                    <li>
                                                        <a href="{{ route('admin.agents.toggleStatus', $agent->id) }}"
                                                        class="dropdown-item text-success"
                                                        onclick="return confirm('Activate this agent?')">
                                                            Activate
                                                        </a>
                                                    </li>
                                                @endif

                                            @endcan

                                        </ul>
                                    </div>
                                    {{-- <button class="btn btn-sm btn-info viewBtn"
                                        data-id="{{ $agent->id }}"
                                        data-bs-toggle="modal"
                                        data-bs-target="#viewModal">
                                        View
                                    </button>
                                    <button class="btn btn-sm btn-warning editBtn"
                                        data-id="{{ $agent->id }}"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editModal">
                                        Edit
                                    </button>
                                    @if($agent->status == 1)
                                        <a href="{{ route('admin.agents.toggleStatus', $agent->id) }}"
                                        class="btn btn-sm btn-danger">
                                        Suspend
                                        </a>
                                    @else
                                        <a href="{{ route('admin.agents.toggleStatus', $agent->id) }}"
                                        class="btn btn-sm btn-success">
                                        Activate
                                        </a>
                                    @endif --}}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <div class="modal fade" id="addAgentModal" tabindex="-1">
        <div class="modal-dialog modal-md">
            <form method="POST" action="{{ route('admin.agents.store') }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header border-0">
                        <h5 class="modal-title fw-bold">Add New Agent</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">

                        <div class="mb-3">
                            <label class="form-label">Agent Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-control">
                                <option value="1" selected>Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>


                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Add Agent</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="viewModal" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">

            <div class="modal-header border-0">
                <div>
                    <h5 class="modal-title fw-bold">Merchant Details</h5>
                    <small class="text-muted">View merchant information</small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <div class="mb-3">
                    <label class="text-muted">Name</label>
                    <div id="view_name" class="fw-semibold"></div>
                </div>

                <div class="mb-3">
                    <label class="text-muted">Email</label>
                    <div id="view_email"></div>
                </div>

                <div class="mb-3">
                    <label class="text-muted">Status</label>
                    <div id="view_status"></div>
                </div>

            </div>

        </div>
    </div>
</div>


<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog modal-md">
        <form method="POST" action="{{ route('admin.agents.update') }}">
            @csrf

            <input type="hidden" name="id" id="edit_id">

            <div class="modal-content">

                <div class="modal-header border-0">
                    <div>
                        <h5 class="modal-title fw-bold">Edit Merchant</h5>
                        <small class="text-muted">Update merchant information</small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" id="edit_name" name="name" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="text" id="edit_email" name="email" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select id="edit_status" name="status" class="form-control">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>

            </div>
        </form>
    </div>
</div>




@endsection


@push('script')

<script>
        function getMerchantUrl(id) {
            let url = "{{ route('admin.agents.show', ':id') }}";
            return url.replace(':id', id);
        }

        // VIEW
        $(document).on('click', '.viewBtn', function () {
            let id = $(this).data('id');

            $.get(getMerchantUrl(id), function (res) {
                let user = res.user;

                $('#view_name').text(user.name);
                $('#view_email').text(user.email);
                $('#view_status').text(user.status == 1 ? 'Active' : 'Inactive');
            });
        });

        // EDIT
        $(document).on('click', '.editBtn', function () {
            let id = $(this).data('id');

            $.get(getMerchantUrl(id), function (res) {
                let user = res.user;

                $('#edit_id').val(user.id);
                $('#edit_name').val(user.name);
                $('#edit_email').val(user.email);
                $('#edit_status').val(user.status);
            });
        });
    </script>

 <script>
        $(document).ready(function () {
            $('#usersTable').DataTable({
                dom:
                    "<'row mb-3'<'col-md-6 d-flex align-items-center'B><'col-md-6 d-flex justify-content-end'f>>" +
                    "<'row'<'col-12'tr>>" +
                    "<'row mt-3'<'col-md-5'l><'col-md-7 d-flex justify-content-end'p>>",

                buttons: [
                    {
                        extend: 'copy',
                        className: 'btn btn-outline-secondary text-light btn-sm me-1',
                        exportOptions: {
                            columns: ':not(:last-child)'
                        }
                    },
                    {
                        extend: 'csv',
                        className: 'btn btn-outline-success text-light btn-sm me-1',
                        exportOptions: {
                            columns: ':not(:last-child)'
                        }
                    },
                    {
                        extend: 'excel',
                        className: 'btn btn-outline-success text-light btn-sm me-1',
                        exportOptions: {
                            columns: ':not(:last-child)'
                        }
                    },
                    {
                        extend: 'pdf',
                        className: 'btn btn-outline-danger text-light btn-sm me-1',
                        exportOptions: {
                            columns: ':not(:last-child)'
                        }
                    },
                    {
                        extend: 'print',
                        className: 'btn btn-outline-primary text-light btn-sm',
                        exportOptions: {
                            columns: ':not(:last-child)'
                        }
                    }
                ],

                lengthMenu: [10, 25, 50, 100],
                pageLength: 10,

                language: {
                    search: "",
                    searchPlaceholder: "🔍 Search users...",
                    lengthMenu: "Show _MENU_ entries",
                }
            });
        });
    </script>
@endpush
