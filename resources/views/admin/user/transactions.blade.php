@extends('admin.layouts.master')
@section('users', 'active')
@section('title') Transactions @endsection

@section('content')
    <main class="container-fluid p-3 p-lg-4">

        <!-- Page Header -->
        <div class="page-header">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                <div class="mb-3 mb-md-0">
                    <h2 class="page-content-title fw-semibold fs-5">Transactions History</h2>
                    <p class="page-subtitle mb-0">Monitor and manage all system transactions</p>
                </div>
            </div>
        </div>

        <!-- ================= FILTER CARD ================= -->


        <!-- ================= TRANSACTION TABLE ================= -->
        <div class="main-card">
            <div class="card-header d-flex justify-content-between align-items-center mb-3">
                <h5 class="card-title mb-0">Transaction List of  {{ $user->name }}</h5>
            </div>

            <div class="table-container">
                <table id="transactionTable" class="data-table table table-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th>Trx ID</th>
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
                                    <div>{{ $trx->merchant->name }}</div>
                                    <small class="text-muted">{{ $trx->merchant->merchant->business_name }}</small>
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
                                            <li>
                                                <button class="dropdown-item">View</button>
                                            </li>

                                            <li>
                                                <button class="dropdown-item">Receipt</button>
                                            </li>

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
                                            @endif
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
