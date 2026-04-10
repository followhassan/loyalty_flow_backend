<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CashbackHistory;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserDashboardController extends Controller
{
     use ApiResponseTrait;

     public function dashboard()
    {
        try {

            $user = Auth::user();

            if (!$user) {
                return $this->errorResponse('Unauthorized', 401);
            }

            // 🔥 Pending (Validation)
            $pending = CashbackHistory::where('user_id', $user->id)
                ->where('status', 0)
                ->sum('amount');

            // 🔥 Matured
            $matured = CashbackHistory::where('user_id', $user->id)
                ->where('status', 1)
                ->sum('amount');

            // 🔥 Paid
            $paid = CashbackHistory::where('user_id', $user->id)
                ->where('status', 2)
                ->sum('amount');

            // 🔥 Remaining balance (optional)
            $remaining = max(0, $matured - $paid);

            $data = [
                'total_balance'    => (float) $user->total_balance, // from users table
                'pending_cashback'=> (float) $pending,
                'total_matured'   => (float) $matured,
                'total_paid'      => (float) $paid,
                'remaining'       => (float) $remaining
            ];

            return $this->successResponse($data, 'User dashboard data', 200);

        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }
}
