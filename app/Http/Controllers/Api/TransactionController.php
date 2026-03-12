<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    use ApiResponseTrait;

    public function submitTransaction(Request $request)
    {
        try {

            $user = Auth::user();

            if (!$user) {
                return $this->errorResponse('Unauthorized', 401);
            }

            // Only customers can submit
            if ($user->user_type != 0) {
                return $this->errorResponse('Only customers can submit transactions', 403);
            }

            $request->validate([
                'merchant_id' => 'required|exists:users,id',
                'amount'      => 'required|numeric|min:1'
            ]);

            $transaction = Transaction::create([
                'customer_id' => $user->id,
                'merchant_id' => $request->merchant_id,
                'amount'      => $request->amount,
                'status'      => 0
            ]);

            return $this->successResponse($transaction, 'Transaction submitted successfully', 201);

        } catch (\Exception $e) {

            return $this->errorResponse($e->getMessage(), 500);

        }
    }
}
