<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MerchentCustomerController extends Controller
{
    use ApiResponseTrait;
    public function customers(Request $request)
    {
        try {

            $merchantUser = Auth::user();

            if (!$merchantUser || $merchantUser->user_type != 1) {
                return $this->errorResponse('Unauthorized', 403);
            }

            // 🔥 Get unique customers
            $customers = User::whereIn('id', function ($query) use ($merchantUser) {
                $query->select('customer_id')
                    ->from('transactions')
                    ->where('merchant_id', $merchantUser->id);
            })
            ->with([
                // 🔥 Transactions with this merchant
                'transactions' => function ($q) use ($merchantUser) {
                    $q->where('merchant_id', $merchantUser->id)
                    ->select('id', 'customer_id', 'merchant_id', 'amount', 'status', 'created_at');
                },

                // 🔥 Cashback history
                'cashbacks' => function ($q) {
                    $q->select('id', 'user_id', 'transaction_id', 'cashback_type', 'amount', 'status', 'created_at');
                }
            ])
            ->select('id', 'name', 'email', 'phone')
            ->get();

            return $this->successResponse($customers, 'Customer list fetched', 200);

        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function customerDetails($id)
    {
        try {

            $merchant = Auth::user();            

            $customer = User::where('id', $id)
                ->whereHas('transactions', function ($q) use ($merchant) {
                    $q->where('merchant_id', $merchant->id);
                })
                ->with([
                    'transactions' => function ($q) use ($merchant) {
                        $q->where('merchant_id', $merchant->id);
                    },
                    'cashbacks'
                ])
                ->first();

            if (!$customer) {
                return $this->errorResponse('Customer not found', 404);
            }

            return $this->successResponse($customer, 'Customer details', 200);

        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }
}
