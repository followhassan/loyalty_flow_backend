<?php

namespace App\Http\Controllers\Api\Merchant;

use App\Http\Controllers\Controller;
use App\Models\Merchant;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Traits\ApiResponseTrait;
use SimpleSoftwareIO\QrCode\Facades\QrCode;


class ProfileController extends Controller
{
    use ApiResponseTrait;

    public function updateMerchant(Request $request)
    {
        try {

            $userDetails = Auth::user();

            $user =  User::find($userDetails->id);
            if (!$user) {
                return $this->errorResponse('Unauthorized', 401);
            }

            // Check if user is merchant
            if ($user->user_type != 1) {
                return $this->errorResponse('Only merchant can update this profile', 403);
            }

            $request->validate([
                'name'          => 'nullable|string|max:255',
                'phone'         => 'nullable|unique:users,phone,' . $user->id,
                'country_code'  => 'nullable|string|max:10',
                'date_of_birth' => 'nullable|date',
                'image'         => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                'business_name' => 'required|string|max:255',
                'address'       => 'required|string|max:255',
                'category'      => 'required|string|max:100',
                'contact_phone' => 'nullable|string|max:20',
            ]);


            if ($request->name) {
                $user->name = $request->name;
            }

            if ($request->phone) {
                $user->phone = $request->phone;
            }

            if ($request->country_code) {
                $user->country_code = $request->country_code;
            }

            if ($request->date_of_birth) {
                $user->date_of_birth = Carbon::parse($request->date_of_birth)->format('Y-m-d');
            }

            // Image upload
            if ($request->hasFile('image')) {

                $file = $request->file('image');
                $filename = time().'_'.$file->getClientOriginalName();

                $file->move(public_path('uploads/profile'), $filename);

                $user->image = 'uploads/profile/'.$filename;
            }

            $user->save();

            $merchant = Merchant::where('user_id', $user->id)->first();

            if (!$merchant) {
                return $this->errorResponse('Merchant profile not found', 404);
            }

            $merchant->business_name = $request->business_name;
            $merchant->address       = $request->address;
            $merchant->category      = $request->category;
            $merchant->contact_phone = $request->contact_phone ?? $merchant->contact_phone;

            $merchant->save();

            $data = [
                'id'            => $user->id,
                'user_id'       => $user->user_id,
                'name'          => $user->name,
                'email'         => $user->email,
                'phone'         => $user->phone,
                'country_code'  => $user->country_code,
                'date_of_birth' => $user->date_of_birth,
                'image'         => $user->image ? asset($user->image) : null,
                'business_name'  => $merchant->business_name,
                'address'        => $merchant->address,
                'category'       => $merchant->category,
                'contact_phone'  => $merchant->contact_phone,
            ];


            return $this->successResponse($data, 'Merchant profile updated successfully', 200);

        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }


    public function merchantQr()
    {
        try {

            $user = Auth::user();

            if (!$user) {
                return $this->errorResponse('Unauthorized', 401);
            }

            // Only merchant allowed
            if ($user->user_type != 1) {
                return $this->errorResponse('Only merchant can access QR', 403);
            }

            $merchant = Merchant::where('user_id', $user->id)->first();

            if (!$merchant) {
                return $this->errorResponse('Merchant profile not found', 404);
            }

            // QR content (what scanner will read)
            $qrData = url('/merchant/'.$user->referral_code);

            $qrImage = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=".$qrData;
            // $qrData = url('/merchant/'.$user->referral_code);

            // // Generate QR
            // $qr = base64_encode(
            //     \QrCode::format('png')->size(300)->generate($qrData)
            // );
            $data = [
                'merchant_id'   => $merchant->id,
                'business_name' => $merchant->business_name,
                'referral_code' => $user->referral_code,
                'qr_data'       => $qrData,
                'qr_image'      => $qrImage
                // 'qr_image'      => 'data:image/png;base64,'.$qr
            ];
            return $this->successResponse($data, 'Merchant QR fetched successfully', 200);

        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }


    public function dashboard()
    {
        try {

            $user = Auth::user();

            if (!$user) {
                return $this->errorResponse('Unauthorized', 401);
            }

            // Only merchant allowed
            if ($user->user_type != 1) {
                return $this->errorResponse('Only merchant can access dashboard', 403);
            }

            // Total transactions
            $totalTransactions = Transaction::where('merchant_id', $user->id)->count();

            // Approved transactions
            $approvedTransactions = Transaction::where('merchant_id', $user->id)
                ->where('status', 1)
                ->count();

            // Pending transactions
            $pendingTransactions = Transaction::where('merchant_id', $user->id)
                ->where('status', 0)
                ->count();

            // Total unique customers
            $totalCustomers = Transaction::where('merchant_id', $user->id)
                ->distinct('customer_id')
                ->count('customer_id');

            $data = [
                'total_customers'       => $totalCustomers,
                'total_transactions'   => $totalTransactions,
                'approved_transactions'=> $approvedTransactions,
                'pending_transactions' => $pendingTransactions,
            ];

            return $this->successResponse($data, 'Dashboard data fetched successfully', 200);

        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);

        }
    }
}
