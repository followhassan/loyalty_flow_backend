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
                <form id="filterForm">
                    <div class="row g-3">

                        <!-- From Date -->
                        <div class="col-md-3">
                            <label class="form-label">From Date</label>
                            <input type="date" name="from_date" class="form-control">
                        </div>

                        <!-- To Date -->
                        <div class="col-md-3">
                            <label class="form-label">To Date</label>
                            <input type="date" name="to_date" class="form-control">
                        </div>

                        <!-- Status -->
                        <div class="col-md-2">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="">All</option>
                                <option value="pending">Pending</option>
                                <option value="approved">Approved</option>
                                <option value="rejected">Rejected</option>
                            </select>
                        </div>

                        <!-- Merchant -->
                        <div class="col-md-3">
                            <label class="form-label">Merchant</label>
                            <input type="text" name="merchant" class="form-control" placeholder="Merchant name">
                        </div>

                        <!-- Filter Button -->
                        <div class="col-md-1 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary-custom w-100">
                                <i class="fa-solid fa-filter me-1"></i>
                            </button>
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
                        <tr>
                            <td>#TRX1001</td>
                            <td>
                                <div>John Doe</div>
                                <small class="text-muted">john@example.com</small>
                            </td>
                            <td>TechZone Electronics</td>
                            <td>$250.00</td>
                            <td class="text-success">$12.50</td>
                            <td><span class="badge bg-success">Completed</span></td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-info">View</button>
                                <button class="btn btn-sm btn-secondary">Receipt</button>
                            </td>
                        </tr>

                        <tr>
                            <td>#TRX1002</td>
                            <td>
                                <div>Sarah Ahmed</div>
                                <small class="text-muted">sarah@mail.com</small>
                            </td>
                            <td>Fashion Hub</td>
                            <td>$120.50</td>
                            <td class="text-success">$6.00</td>
                            <td><span class="badge bg-warning text-dark">Pending</span></td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-info">View</button>
                                <button class="btn btn-sm btn-danger">Cancel</button>
                            </td>
                        </tr>

                        <tr>
                            <td>#TRX1003</td>
                            <td>
                                <div>Michael Smith</div>
                                <small class="text-muted">michael@gmail.com</small>
                            </td>
                            <td>Fresh Mart Grocery</td>
                            <td>$80.00</td>
                            <td class="text-success">$4.00</td>
                            <td><span class="badge bg-danger">Failed</span></td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-info">View</button>
                                <button class="btn btn-sm btn-warning">Retry</button>
                            </td>
                        </tr>

                        <tr>
                            <td>#TRX1004</td>
                            <td>
                                <div>Emma Watson</div>
                                <small class="text-muted">emma@yahoo.com</small>
                            </td>
                            <td>Digital World</td>
                            <td>$980.75</td>
                            <td class="text-success">$49.00</td>
                            <td><span class="badge bg-success">Completed</span></td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-info">View</button>
                                <button class="btn btn-sm btn-secondary">Receipt</button>
                            </td>
                        </tr>

                        <tr>
                            <td>#TRX1005</td>
                            <td>
                                <div>Rahim Uddin</div>
                                <small class="text-muted">rahim@mail.com</small>
                            </td>
                            <td>Urban Lifestyle</td>
                            <td>$320.00</td>
                            <td class="text-success">$16.00</td>
                            <td><span class="badge bg-success">Completed</span></td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-info">View</button>
                                <button class="btn btn-sm btn-secondary">Receipt</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </main>
@endsection
