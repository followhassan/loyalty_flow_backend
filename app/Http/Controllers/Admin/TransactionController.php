<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CashbackHistory;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index()
    {
        // if (is_null($this->user) || !$this->user->can('admin.user.view')) {
        //         abort(403, 'Sorry !! You are Unauthorized.');
        // }
        $data['title'] = 'Transactions';
        $data['transactions'] = Transaction::orderBy('id', 'desc')->get();

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

        $cashback->status = 2;
        $cashback->save();

        return back()->with('success', 'Cashback marked as Paid');
    }
}
