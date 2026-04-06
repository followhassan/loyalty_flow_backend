<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\MerchantOtpMail;
use App\Mail\WelcomeUserMail;
use App\Models\Agent;
use App\Models\Merchant;
use App\Models\User;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    use ApiResponseTrait;


    public function login(Request $request)
    {
        try {
            // Validate input
            $request->validate([
                'email'    => 'required|email',
                'password' => 'required',
            ]);

            $user = User::where('email', $request->email)->first();

            if (!$user) {
                return $this->errorResponse('Invalid credentials', 401);
            }

            if (!Hash::check($request->password, $user->password)) {
                return $this->errorResponse('Invalid credentials', 401);
            }

            // OTP check
            if ($user->is_verified == 0) {
                return $this->errorResponse('Account not verified. Please verify OTP.', 403);
            }

            // Account active check
            if ($user->status == 0) {
                return $this->errorResponse('Account is pending need to approval by admin.', 403);
            }

            // Generate JWT token
            $token = JWTAuth::fromUser($user);

             if ($user->user_type == 0) {
                $userType = 'customer';
            } elseif ($user->user_type == 1) {
                $userType = 'Merchant';
            } elseif ($user->user_type == 2) {
                $userType = 'Agent';
            }

            $data = [
                'user'  => [
                    'id'           => $user->id,
                    'user_id'      => $user->user_id,
                    'name'         => $user->name,
                    'email'        => $user->email,
                    'phone'        => $user->phone,
                    'user_type'    => $userType,
                    'referral_code'=> $user->referral_code,
                    'role_status'  => $user->role_status,
                    'status'       => $user->status,
                ],
                'token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth('api')->factory()->getTTL() * 60
            ];

            return $this->successResponse($data, 'Login successful', 200);

        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }


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
                'user_type'     => 'required|in:0,1,2',
                'business_name' => 'required_if:user_type,1|string|max:255',
                'address'       => 'required_if:user_type,1|string|max:255',
                'category'      => 'required_if:user_type,1|string|max:100',
                'referral_code' => 'nullable|string|max:50',
            ]);

            $otp = rand(100000, 999999);

            if ($request->user_type == 0) {
                $prefix = 'U';
            } elseif ($request->user_type == 1) {
                $prefix = 'M';
            } elseif ($request->user_type == 2) {
                $prefix = 'A';
            }

            $lastUser = User::where('user_type', $request->user_type)
            ->orderBy('id', 'desc')
            ->first();

            if ($lastUser && $lastUser->user_id) {
                $number = (int) substr($lastUser->user_id, 1);
                $number++;
            } else {
                $number = 1001;
            }

            $userId = $prefix . $number;

            $referrerId = null;
            if ($request->filled('ref')) {
                $referrer = User::where('referral_code', $request->ref)->first();
                if ($referrer) {
                    $referrerId = $referrer->id;
                }
            }

            $user                    = new User();
            $user->user_id           = $userId;
            $user->name              = $request->name;
            $user->email             = $request->email;
            $user->phone             = $request->phone;
            $user->country_code      = $request->country_code;
            $user->password          = Hash::make($request->password);
            $user->user_type         = $request->user_type; // Merchant
            $user->verification_code = $otp;
            $user->is_verified       = 0;
            $user->date_of_birth     = $request->date_of_birth;
            $user->referral_code     = $this->generateReferralCode($prefix);
            $user->referred_by       = $referrerId;
            $user->save();

            // Send OTP Email

            // Create merchant or agent record if needed
            if ($request->user_type == 1) { // Merchant
                Merchant::create([
                    'user_id'       => $user->id,
                    'agent_id'      => $referrerId,
                    'business_name' => $request->business_name,
                    'address'       => $request->address,
                    'category'      => $request->category,
                    'contact_phone' => $request->phone,
                ]);
            }

            if ($request->user_type == 2) { // Agent
                Agent::create([
                    'user_id'       => $user->id,
                    'referral_code' => $user->referral_code
                ]);
            }

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

    public function verifyOtp(Request $request)
    {
        try {

            $request->validate([
                'email' => 'required|email',
                'otp'   => 'required'
            ]);

            $user = User::where('email', $request->email)
                        ->where('verification_code', $request->otp)
                        ->first();

            if (!$user) {
                return $this->errorResponse('Invalid OTP', 400);
            }

            $user->is_verified = 1;
            $user->verification_code = null;
            $user->email_verified_at = now();


            if ($user->user_type == 0) {
                $user->status = 1;
            }
            $user->save();

            try {
                Mail::to($user->email)->send(new WelcomeUserMail($user));
            } catch (\Exception $e) {
                \Log::error('Welcome mail failed: '.$e->getMessage());
            }

            return $this->successResponse(null, 'Account verified successfully', 200);

        } catch (\Exception $e) {

            return $this->errorResponse($e->getMessage(), 500);
        }
    }


    public function resendOtp(Request $request)
    {
        try {

            $request->validate([
                'email' => 'required|email'
            ]);

            $user = User::where('email', $request->email)->first();

            if (!$user) {
                return $this->errorResponse('User not found', 404);
            }

            // Optional: prevent resend if already verified
            if ($user->is_verified == 1) {
                return $this->errorResponse('User already verified', 400);
            }

            // Generate new OTP
            $otp = rand(100000, 999999);

            // Update user OTP
            $user->verification_code = $otp;
            $user->save();

            // Send email
            try {
                Mail::to($user->email)->send(new MerchantOtpMail($otp, $user->name));
            } catch (\Exception $e) {
                Log::error('Resend OTP mail failed: '.$e->getMessage());
            }

            return $this->successResponse(null, 'OTP resent successfully', 200);

        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }


    public function forgotPassword(Request $request)
    {
        try {

            $request->validate([
                'email' => 'required|email'
            ]);

            $user = User::where('email', $request->email)->first();

            if (!$user) {
                return $this->errorResponse('User not found', 404);
            }

            $otp = rand(100000, 999999);

            $user->verification_code = $otp;
            $user->save();

            try {
                Mail::to($user->email)->send(new MerchantOtpMail($otp, $user->name));
            } catch (\Exception $e) {
                Log::error('Forgot password OTP mail failed: '.$e->getMessage());
            }

            return $this->successResponse(null, 'OTP sent to your email', 200);

        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }
    public function verifyForgetPasswordOtp(Request $request)
    {
        try {

            $request->validate([
                'email' => 'required|email',
                'otp'   => 'required'
            ]);

            $user = User::where('email', $request->email)
                        ->where('verification_code', $request->otp)
                        ->first();

            if (!$user) {
                return $this->errorResponse('Invalid OTP', 400);
            }

            $user->is_verified = 1;
            $user->verification_code = null;
            $user->email_verified_at = now();


            if ($user->user_type == 0) {
                $user->status = 1;
            }
            $user->save();

            return $this->successResponse(null, 'OTP verified successfully', 200);

        } catch (\Exception $e) {

            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function resetPassword(Request $request)
    {
        try {

            $request->validate([
                'email'    => 'required|email',
                'otp'      => 'required',
                'password' => 'required|min:6|confirmed'
            ]);

            $user = User::where('email', $request->email)
                        ->where('verification_code', $request->otp)
                        ->first();

            if (!$user) {
                return $this->errorResponse('Invalid OTP', 400);
            }

            $user->password = Hash::make($request->password);
            $user->verification_code = null; // clear OTP
            $user->save();

            return $this->successResponse(null, 'Password reset successfully', 200);

        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function profile(Request $request)
    {
        try {
            $user = Auth::user(); // Authenticated user

            if (!$user) {
                return $this->errorResponse('User not found', 404);
            }

            // Base profile data
            $profile = [
                'id'           => $user->id,
                'user_id'      => $user->user_id,
                'name'         => $user->name,
                'email'        => $user->email,
                'phone'        => $user->phone,
                'country_code' => $user->country_code,
                'date_of_birth'=> $user->date_of_birth,
                'user_type'    => $user->user_type, // 0=User,1=Merchant,2=Agent
                'referral_code'=> $user->referral_code,
                'referred_by'  => $user->referred_by,
                'role_status'  => $user->role_status,
                'status'       => $user->status,
                'created_at'   => $user->created_at,
                'updated_at'   => $user->updated_at,
            ];

            // Merchant extra info
            if ($user->user_type == 1) {
                $merchant = Merchant::where('user_id', $user->id)->first();
                if ($merchant) {
                    $profile['merchant'] = [
                        'business_name' => $merchant->business_name,
                        'address'       => $merchant->address,
                        'category'      => $merchant->category,
                        'contact_phone' => $merchant->contact_phone,
                        'status'        => $merchant->status,
                    ];
                }
            }

            // Agent extra info
            if ($user->user_type == 2) {
                $agent = Agent::where('user_id', $user->id)->first();
                if ($agent) {
                    $profile['agent'] = [
                        'referral_code' => $agent->referral_code,
                    ];
                }
            }

            return $this->successResponse($profile, 'Profile fetched successfully', 200);

        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    private function generateReferralCode($prefix)
    {
        do {
            $code = $prefix . strtoupper(Str::random(6));
        } while(User::where('referral_code', $code)->exists());

        return $code;
    }

    public function switchRole(Request $request)
    {
        try {

            $userData = Auth::user();

            $user = User::find($userData->id);

            if (!$user) {
                return $this->errorResponse('Unauthorized', 401);
            }

            $request->validate([
                'user_type' => 'required|in:0,1,2'
            ]);

            $targetRole = $request->user_type;         

            // 🔥 Switch to Merchant
            if ($targetRole == 1) {
                $merchant = Merchant::where('user_id', $user->id)->first();

                // ❗ If NOT exists → create basic merchant
                if (!$merchant) {

                    Merchant::create([
                        'user_id'       => $user->id,
                        'business_name' => '',
                        'address'       => '',
                        'category'      => '',
                        'contact_phone' => $user->phone,
                        'agent_id'      => null,
                    ]);

                    // ✅ Switch role
                    $user->user_type = 1;
                    $user->save();

                    return $this->successResponse(null, 'Merchant profile created. Please complete business details and wait for admin approval.', 200);
                }

                // Optional: check approval
                if (isset($merchant->status) && $merchant->status != 1) {
                    return $this->errorResponse('Merchant not approved yet', 403);
                }
            }

            // 🔥 Switch to Agent
            if ($targetRole == 2) {

                $agent = Agent::where('user_id', $user->id)->first();

                // Auto create agent if not exists (optional)
                if (!$agent) {

                    Agent::create([
                        'user_id'       => $user->id,
                        'referral_code' => $user->referral_code
                    ]);

                    // ✅ Switch role
                    $user->user_type = 2;
                    $user->save();

                    return $this->successResponse(null, 'Agent profile created. You can now switch after setup.', 200);
                }
            }

            if($targetRole == 0) {
                // Optional: Add any checks before switching back to customer
                $user->user_type = 0;
                $user->save();
            }

           

            return $this->successResponse([
                'user_details' => $user,
                'user_type' => $user->user_type
            ], 'Role switched successfully', 200);

        } catch (\Exception $e) {

            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function applyMerchant(Request $request)
    {
        try {

            $user = Auth::user();

            if (!$user) {
                return $this->errorResponse('Unauthorized', 401);
            }

            $request->validate([
                'business_name' => 'required|string|max:255',
                'address'       => 'required|string|max:255',
                'category'      => 'required|string|max:100',
                'contact_phone' => 'required|string|max:20',
            ]);

            // Already exists?
            $exists = Merchant::where('user_id', $user->id)->first();

            if ($exists) {

                $exists->business_name = $request->business_name;
                $exists->address       = $request->address;
                $exists->category      = $request->category;
                $exists->contact_phone = $request->contact_phone;                
                $exists->save();

            }else{

                return $this->errorResponse('Merchant profile not found.', 400);
            }


            $user ->status = 0; // switch to merchant role
            $user->save();

            return $this->successResponse(null, 'Merchant application submitted. Wait for admin approval.', 200);

        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }


    public function logout(Request $request)
    {
        try {

            $user = Auth::user();

            if (!$user) {
                return $this->errorResponse('Unauthorized', 401);
            }

            // Invalidate JWT token
            auth('api')->logout();

            return $this->successResponse(null, 'Logged out successfully', 200);

        } catch (\Exception $e) {

            return $this->errorResponse($e->getMessage(), 500);

        }
    }

    public function refresh()
    {
        try {

            // Get current token
            $oldToken = JWTAuth::getToken();

            if (!$oldToken) {
                return $this->errorResponse('Token not provided', 401);
            }

            // Refresh token
            $newToken = JWTAuth::refresh($oldToken);

            return $this->successResponse([
                'access_token' => $newToken,
                'token_type'   => 'bearer',
                'expires_in'   => auth('api')->factory()->getTTL() * 60
            ], 'Token refreshed successfully', 200);

        } catch (\Exception $e) {

            return $this->errorResponse($e->getMessage(), 500);
        }
    }
}
