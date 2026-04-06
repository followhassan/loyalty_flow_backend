<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $data['title'] = "Dashboard";
        $data['totalMerchants'] = User::where('user_type', 1)->where('status', 1)->count();
        $data['totalAgents'] = User::where('user_type', 2)->where('status', 1)->count();
        $data['totalCustomers'] = User::where('user_type', 0)->where('status', 1)->count();
        $data['totalTransactions'] = Transaction::sum('amount');
        $data['recentTransactions'] = Transaction::latest()
            ->take(7)
            ->get();

        $transactions = Transaction::select(
            DB::raw("SUM(amount) as total"),
            DB::raw("DATE_FORMAT(created_at, '%b') as month"),
            DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month_key")
            )
            ->where('created_at', '>=', Carbon::now()->subMonths(6))
            ->groupBy('month', 'month_key')
            ->orderBy('month_key')
            ->get();

        $data['labels'] = $transactions->pluck('month');
        $data['values'] = $transactions->pluck('total');

        return view('admin.dashboard', compact('data'));
    }
}
