<?php

namespace App\Http\Controllers\Api\Merchant;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    use ApiResponseTrait;

    public function merchantTransactions()
    {
        try {

            $user = Auth::user();

            if (!$user) {
                return $this->errorResponse('Unauthorized', 401);
            }

            // Only merchant
            if ($user->user_type != 1) {
                return $this->errorResponse('Only merchant can access this', 403);
            }

            $transactions = Transaction::where('merchant_id', $user->id)
                ->with(['customer'])
                ->orderBy('id', 'desc')
                ->get();

            // Format response
            $data = $transactions->map(function ($t) {
                return [
                    'id'        => $t->id,
                    'amount'    => $t->amount,
                    'status'    => $t->status,
                    'created_at' => $t->created_at,
                    'customer'  => [
                        'id'    => $t->customer->id,
                        'name'  => $t->customer->name,
                        'phone' => $t->customer->phone,
                    ]
                ];
            });

            return $this->successResponse($data, 'Merchant transactions fetched', 200);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function approveTransaction($id)
    {
        try {

            $user = Auth::user();

            if (!$user || $user->user_type != 1) {
                return $this->errorResponse('Unauthorized', 403);
            }

            $transaction = Transaction::where('id', $id)
                ->where('merchant_id', $user->id)
                ->first();

            if (!$transaction) {
                return $this->errorResponse('Transaction not found', 404);
            }

            if ($transaction->status == 0 && $transaction->status == 1) {
                return $this->errorResponse('Already processed', 400);
            }

            $transaction->status = 1;
            $transaction->save();

            return $this->successResponse($transaction, 'Approved', 200);

        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function rejectTransaction($id)
    {
        try {

            $user = Auth::user();

            if (!$user || $user->user_type != 1) {
                return $this->errorResponse('Unauthorized', 403);
            }

            $transaction = Transaction::where('id', $id)
                ->where('merchant_id', $user->id)
                ->first();

            if (!$transaction) {
                return $this->errorResponse('Transaction not found', 404);
            }

            if ($transaction->status != 0 && $transaction->status != 1) {
                return $this->errorResponse('Already processed', 400);
            }

            $transaction->status = 2;
            $transaction->save();

            return $this->successResponse($transaction, 'Rejected', 200);

        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }
}
