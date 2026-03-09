<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\MerchantOtpMail;
use App\Models\User;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class MerchantAuthController extends Controller
{
    use ApiResponseTrait;

    public function register(Request $request)
    {
        DB::beginTransaction();

        try {

            $request->validate([
                'name'          => 'required|string|max:255',
                'email'         => 'required|email|unique:users,email',
                'phone'         => 'required|unique:users,phone',
                'password'      => 'required|min:6|confirmed',
                'country_code'  => 'required|string|max:10',
                'date_of_birth' => 'required',
            ]);

            $otp = rand(100000, 999999);

            $user                    = new User();
            $user->name              = $request->name;
            $user->email             = $request->email;
            $user->phone             = $request->phone;
            $user->country_code      = $request->country_code;
            $user->password          = Hash::make($request->password);
            $user->user_type         = $request->user_type; // Merchant
            $user->verification_code = $otp;
            $user->is_verified       = 0;
            $user->date_of_birth     = $request->date_of_birth;
            $user->save();

            // Send OTP Email
            try {
                Mail::to($user->email)->send(new MerchantOtpMail($otp, $user->name));
            } catch (\Exception $e) {
                // Log the error for developer
                Log::error('Mail sending failed: '.$e->getMessage());
            }

            DB::commit();

            return $this->successResponse(null, 'OTP sent to your email. Please verify.', 201);

        } catch (\Exception $e) {

            DB::rollBack();

            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    
}
