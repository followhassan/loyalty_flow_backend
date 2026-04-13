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
                {{-- <div class="d-flex align-items-center gap-2">
                    <button class="btn btn-primary-custom" data-bs-toggle="modal" data-bs-target="#addUserModal">
                        <i class="fa-solid fa-plus me-1"></i> Export
                    </button>
                </div> --}}
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
                <table id="merchentsTable" class="data-table table table-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th>Merchents ID</th>
                            <th>Merchent  Details</th>
                            {{-- <th>Country</th> --}}
                            <th>Address</th>
                            <th>Total Transcations</th>
                            <th>Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($merchents as $item)
                            <tr>
                                <td>#{{ $item->user_id }}</td>
                                <td>
                                    <div class="fw-semibold">{{ $item->name }}</div>

                                    <small class="text-muted d-block">
                                        {{ $item->merchant->business_name ?? 'No Shop Name' }}
                                    </small>

                                    <small class="text-muted">
                                        {{ $item->email }}
                                    </small>
                                </td>
                                <td>{{ $item->merchant->address ?? '' }}</td>
                                <td>
                                    {{ $item->merchant_transactions_count }} Transactions <br>
                                    <small class="text-muted">
                                        ${{ $item->merchant_transactions_sum_amount ?? 0 }}
                                    </small>
                                </td>
                                <td>
                                    @if ($item->status == 1)
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
                                            @can('view merchants')
                                                <li>
                                                    <button class="dropdown-item viewBtn"
                                                        data-id="{{ $item->id }}"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#viewModal">
                                                        View
                                                    </button>
                                                </li>
                                            @endcan

                                            {{-- Edit --}}
                                            @can('update merchant')
                                                <li>
                                                    <button class="dropdown-item editBtn"
                                                        data-id="{{ $item->id }}"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#editModal">
                                                        Edit
                                                    </button>
                                                </li>
                                            @endcan

                                            {{-- 🔥 Merchant Transactions --}}
                                            @can('view merchant customer')
                                                <li>
                                                    <a href="{{ route('admin.merchants.customers', $item->id) }}" class="dropdown-item">
                                                        Customers
                                                    </a>
                                                </li>
                                            @endcan
                                            {{-- <li>
                                                <a href="{{ route('admin.merchant.transactions', $item->id) }}"
                                                class="dropdown-item">
                                                    Transactions
                                                </a>
                                            </li> --}}

                                            @can('update merchant')
                                                <li><hr class="dropdown-divider"></li>

                                                {{-- Status Toggle --}}
                                                @if($item->status == 1)
                                                    <li>
                                                        <a href="{{ route('admin.merchants.toggleStatus', $item->id) }}"
                                                        class="dropdown-item text-danger"
                                                        onclick="return confirm('Suspend this merchant?')">
                                                            Suspend
                                                        </a>
                                                    </li>
                                                @else
                                                    <li>
                                                        <a href="{{ route('admin.merchants.toggleStatus', $item->id) }}"
                                                        class="dropdown-item text-success"
                                                        onclick="return confirm('Activate this merchant?')">
                                                            Activate
                                                        </a>
                                                    </li>
                                                @endif
                                            @endcan

                                        </ul>
                                    </div>
                                    {{-- <button class="btn btn-sm btn-info viewBtn"
                                        data-id="{{ $item->id }}"
                                        data-bs-toggle="modal"
                                        data-bs-target="#viewModal">
                                        View
                                    </button>
                                    <button class="btn btn-sm btn-warning editBtn"
                                        data-id="{{ $item->id }}"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editModal">
                                        Edit
                                    </button>
                                    @if($item->status == 1)
                                        <a href="{{ route('admin.merchants.toggleStatus', $item->id) }}"
                                        class="btn btn-sm btn-danger">
                                        Suspend
                                        </a>
                                    @else
                                        <a href="{{ route('admin.merchants.toggleStatus', $item->id) }}"
                                        class="btn btn-sm btn-success">
                                        Activate
                                        </a>
                                    @endif --}}
                                    {{-- <button class="btn btn-sm btn-danger">Suspend</button> --}}
                                </td>
                            </tr>
                        @endforeach

                        {{-- <tr>
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
                        </tr> --}}
                    </tbody>
                </table>
            </div>
        </div>
    </main>


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
                    <label class="text-muted">Business Name</label>
                    <div id="view_business"></div>
                </div>

                <div class="mb-3">
                    <label class="text-muted">Address</label>
                    <div id="view_address"></div>
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
        <form method="POST" action="{{ route('admin.merchants.update') }}">
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
                        <label class="form-label">Business Name</label>
                        <input type="text" id="edit_business" name="business_name" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Address</label>
                        <input type="text" id="edit_address" name="address" class="form-control">
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
            let url = "{{ route('admin.merchants.show', ':id') }}";
            return url.replace(':id', id);
        }

        // VIEW
        $(document).on('click', '.viewBtn', function () {
            let id = $(this).data('id');

            $.get(getMerchantUrl(id), function (res) {
                let user = res.user;

                $('#view_name').text(user.name);
                $('#view_email').text(user.email);
                $('#view_business').text(user.merchant?.business_name ?? '');
                $('#view_address').text(user.merchant?.address ?? '');
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
                $('#edit_business').val(user.merchant?.business_name ?? '');
                $('#edit_address').val(user.merchant?.address ?? '');
                $('#edit_status').val(user.status);
            });
        });
    </script>

 <script>
        $(document).ready(function () {
            $('#merchentsTable').DataTable({
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
