<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{

    use ApiResponseTrait;

    public function myTransactions()
    {
        try {

            $user = Auth::user();

            if (!$user) {
                return $this->errorResponse('Unauthorized', 401);
            }

            $transactions = Transaction::where('customer_id', $user->id)
                ->with(['merchant', 'customer'])
                ->orderBy('id', 'desc')
                ->get();

            return $this->successResponse($transactions, 'Transactions fetched successfully', 200);

        } catch (\Exception $e) {

            return $this->errorResponse($e->getMessage(), 500);

        }
    }
}
