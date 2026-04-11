<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CashbackHistory;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        // if (is_null($this->user) || !$this->user->can('admin.user.view')) {
        //         abort(403, 'Sorry !! You are Unauthorized.');
        // }
        $data['title'] = 'Transactions';

    $query = Transaction::with(['customer', 'merchant', 'cashback'])
        ->orderBy('id', 'desc');

    // =========================
    // 🔹 Date Filter
    // =========================
    if ($request->filled('from_date')) {
        $query->whereDate('created_at', '>=', $request->from_date);
    }

    if ($request->filled('to_date')) {
        $query->whereDate('created_at', '<=', $request->to_date);
    }

    // =========================
    // 🔹 Status Filter
    // =========================
    if ($request->filled('status')) {

        $statusMap = [
            'pending'  => 0,
            'approved' => 1,
            'rejected' => 2,
        ];

        if (isset($statusMap[$request->status])) {
            $query->where('status', $statusMap[$request->status]);
        }
    }

    // =========================
    // 🔹 Merchant Filter
    // =========================
    if ($request->filled('merchant')) {
      $query->whereHas('merchant.merchant', function ($q) use ($request) {
        $q->where('business_name', 'like', '%' . $request->merchant . '%');
    });
}

    // =========================
    // 🔹 User Filter (optional but useful)
    // =========================
    if ($request->filled('user')) {
        $query->whereHas('customer', function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->user . '%')
            ->orWhere('email', 'like', '%' . $request->user . '%');
        });
    }

    // =========================
    // 🔹 Cashback Status Filter (optional upgrade)
    // =========================
    if ($request->filled('cashback_status')) {
        $query->whereHas('cashback', function ($q) use ($request) {
            $q->where('status', $request->cashback_status);
        });
    }

    // =========================
    // 🔹 Get Data
    // =========================
    $data['transactions'] = $query->paginate(20)->withQueryString();
        // $data['transactions'] = Transaction::orderBy('id', 'desc')->get();

        return view('admin.transactions.index', $data);
    }

    public function markMature($id)
    {
        $cashback = CashbackHistory::findOrFail($id);

        if ($cashback->status != 0) {
            return back()->with('error', 'Invalid action');
        }

        $cashback->status = 1;
        $cashback->save();

        return back()->with('success', 'Cashback marked as Matured');
    }

    public function markPaid($id)
    {
         $cashback = CashbackHistory::findOrFail($id);

        if ($cashback->status != 1) {
            return back()->with('error', 'Invalid action');
        }

        DB::beginTransaction();

        try {
            // 👉 Update cashback status
            $cashback->status = 2;
            $cashback->save();

            // 👉 Add amount to user balance
            $user = $cashback->user; // assuming relation exists

            $user->total_balance -= $cashback->amount;
            $user->save();

            DB::commit();

            return back()->with('success', 'Cashback marked as Paid & balance updated');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
        // $cashback = CashbackHistory::findOrFail($id);

        // if ($cashback->status != 1) {
        //     return back()->with('error', 'Invalid action');
        // }

        // $cashback->status = 2;
        // $cashback->save();

        // return back()->with('success', 'Cashback marked as Paid');
    }
}
