<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Merchant;
use App\Models\User;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class QrController extends Controller
{
    use ApiResponseTrait;

    public function scanQr(Request $request)
    {
        try {

            $request->validate([
                'referral_code' => 'required|string'
            ]);

            $merchantUser = User::where('referral_code', $request->referral_code)
                            ->where('user_type', 1)
                            ->first();

            if (!$merchantUser) {
                return $this->errorResponse('Merchant not found', 404);
            }

            $merchant = Merchant::where('user_id', $merchantUser->id)->first();

            if (!$merchant) {
                return $this->errorResponse('Merchant profile not found', 404);
            }

            $data = [
                'merchant'      => $merchantUser,
                'merchant_id'   => $merchant->id,
                'business_name' => $merchant->business_name,
                'address'       => $merchant->address,
                'category'      => $merchant->category,
                'contact_phone' => $merchant->contact_phone
            ];

            return $this->successResponse($data, 'Merchant found', 200);

        } catch (\Exception $e) {

            return $this->errorResponse($e->getMessage(), 500);

        }
    }
}
