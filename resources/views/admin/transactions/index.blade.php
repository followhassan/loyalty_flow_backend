@extends('admin.layouts.master')
@section('transactions', 'active')
@section('title') Transactions @endsection

@section('content')
    <main class="container-fluid p-3 p-lg-4">

        <!-- Page Header -->
        <div class="page-header">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                <div class="mb-3 mb-md-0">
                    <h2 class="page-content-title fw-semibold fs-5">Transactions</h2>
                    <p class="page-subtitle mb-0">Monitor and manage all system transactions</p>
                </div>
            </div>
        </div>

        <!-- ================= FILTER CARD ================= -->
        <div class="main-card mb-4 p-2">
            <div class="card-header mb-3">
                <h5 class="card-title mb-0">Filter Transactions</h5>
            </div>

            <div class="card-body">
                <form id="filterForm" method="GET" action="{{ route('admin.transactions.index') }}">
                    <div class="row g-3">

                        <!-- From Date -->
                        <div class="col-md-3">
                            <label class="form-label">From Date</label>
                            <input type="date" name="from_date" value="{{ request('from_date') }}" class="form-control">
                        </div>

                        <!-- To Date -->
                        <div class="col-md-3">
                            <label class="form-label">To Date</label>
                            <input type="date" name="to_date" value="{{ request('to_date') }}" class="form-control">
                        </div>

                        <!-- Status -->
                        <div class="col-md-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="">All</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>

                        <!-- Merchant -->
                        <div class="col-md-3">
                            <label class="form-label">Merchant</label>
                            <input type="text" name="merchant"  value="{{ request('merchant') }}" class="form-control" placeholder="Merchant name">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">User</label>
                            <input type="text" name="user"  value="{{ request('user') }}" class="form-control" placeholder="User name or email">
                        </div>

                        <!-- Filter Button -->
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary-custom w-100">
                                <i class="fa-solid fa-filter me-1"></i> Filter
                            </button>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <a href="{{ route('admin.transactions.index') }}" class="btn btn-secondary w-100" style="padding: 8px;">
                                Reset
                            </a>
                        </div>

                    </div>
                </form>
            </div>
        </div>

        <!-- ================= TRANSACTION TABLE ================= -->
        <div class="main-card">
            <div class="card-header d-flex justify-content-between align-items-center mb-3">
                <h5 class="card-title mb-0">Transaction List</h5>
            </div>

            <div class="table-container">
                <table id="transactionTable" class="data-table table table-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th>Trx ID</th>
                            <th>User</th>
                            <th>Merchant</th>
                            <th>Amount</th>
                            <th>Cashback</th>
                            <th>Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transactions as $trx)
                            <tr>
                                <td>#TRX1001</td>
                                <td>
                                    <div>{{ $trx->customer->name }}</div>
                                    <small class="text-muted">{{ $trx->customer->email }}</small>
                                </td>
                                <td>
                                    <div>{{ $trx->merchant->name }}</div>
                                    <small class="text-muted">{{ $trx->merchant->merchant?->business_name }}</small>
                                </td>
                                <td>${{ $trx->amount }}</td>
                                <td class="text-success">
                                    @if ($trx->status == 1)
                                    <span>${{ $trx->cashback->amount ?? '00' }}</span><br>
                                    <small>
                                        @if ($trx->cashback)
                                            {{ $trx->cashback->status == 1 ? 'Matured' : ($trx->cashback->status == 2 ? 'Paid' : 'In Validation') }}
                                        @else
                                            No Cashback
                                        @endif
                                    </small>
                                    @endif
                                </td>
                                <td>
                                    @if ($trx->status == 1)
                                        <span class="badge bg-success">Approved</span>
                                    @elseif ($trx->status == 2)
                                        <span class="badge bg-danger">Rejected</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @endif

                                </td>
                                <td class="text-center">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            Actions
                                        </button>

                                        <ul class="dropdown-menu">
                                            {{-- @can('view transactions')
                                                <li>
                                                    <button class="dropdown-item">View</button>
                                                </li>
                                            @endcan

                                            @can('view transactions')
                                                <li>
                                                    <button class="dropdown-item">Receipt</button>
                                                </li>
                                            @endcan --}}

                                            @can('transactions edit')
                                                @if($trx->status == 1 && $trx->cashback)
                                                    <li><hr class="dropdown-divider"></li>

                                                    {{-- Mark as Mature --}}
                                                    @if($trx->cashback->status == 0)
                                                        <li>
                                                            <a href="{{ route('admin.transactions.cashback.mature', $trx->cashback->id) }}"
                                                            class="dropdown-item text-warning">
                                                                Mark as Mature
                                                            </a>
                                                        </li>
                                                    @endif

                                                    {{-- Mark as Paid --}}
                                                    @if($trx->cashback->status == 1)
                                                        <li>
                                                            <a href="{{ route('admin.transactions.cashback.paid', $trx->cashback->id) }}"
                                                            class="dropdown-item text-success">
                                                                Mark as Paid
                                                            </a>
                                                        </li>
                                                    @endif
                                                    @if($trx->cashback->status == 2)
                                                        <li>
                                                            <span
                                                            class="dropdown-item text-success">
                                                                Already Paid
                                                            </span>
                                                        </li>
                                                    @endif
                                                @endif
                                            @endcan
                                        </ul>
                                    </div>
                                    {{-- <button class="btn btn-sm btn-info">View</button>
                                    <button class="btn btn-sm btn-secondary">Receipt</button> --}}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </main>
@endsection
