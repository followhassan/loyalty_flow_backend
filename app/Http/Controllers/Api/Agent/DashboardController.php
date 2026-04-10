<?php

namespace App\Http\Controllers\Api\Agent;

use App\Http\Controllers\Controller;
use App\Models\Merchant;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    use ApiResponseTrait;

    public function dashboard()
    {
        try {

            $user = Auth::user();

            if (!$user) {
                return $this->errorResponse('Unauthorized', 401);
            }

            // Only agent allowed
            if ($user->user_type != 2) {
                return $this->errorResponse('Only agent can access dashboard', 403);
            }

            // Total merchants under this agent
            $totalMerchants = Merchant::where('agent_id', $user->id)->count();

            // Active merchants
            $activeMerchants = Merchant::where('agent_id', $user->id)
                ->whereHas('user', function ($q) {
                    $q->where('status', 1);
                })
                ->count();

            // Pending merchants
            $pendingMerchants = Merchant::where('agent_id', $user->id)
                ->whereHas('user', function ($q) {
                    $q->where('status', 0);
                })
                ->count();

            $data = [
                'total_merchants'   => $totalMerchants,
                'active_merchants'  => $activeMerchants,
                'pending_merchants' => $pendingMerchants,
            ];

            return $this->successResponse($data, 'Agent dashboard fetched successfully', 200);

        } catch (\Exception $e) {

            return $this->errorResponse($e->getMessage(), 500);

        }
    }

    public function referral()
    {
        try {

            $user = Auth::user();

            if (!$user || $user->user_type != 2) {
                return $this->errorResponse('Unauthorized', 403);
            }

            $data = [
                'referral_code' => $user->referral_code,
                'referral_link' => url('/api/auth/register?ref=' . $user->referral_code)
            ];

            return $this->successResponse($data, 'Referral info fetched', 200);

        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }


    public function merchants()
    {
        try {

            $agent = Auth::user();

            if (!$agent || $agent->user_type != 2) {
                return $this->errorResponse('Unauthorized', 403);
            }

            $merchants = Merchant::where('agent_id', $agent->id)
                ->with(['user:id,name,email,phone','status'])
                ->select('id','user_id','business_name','created_at')
                ->latest()
                ->get();

            return $this->successResponse($merchants, 'Merchant list fetched', 200);

        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function growth()
    {
        try {

            $agent = Auth::user();

            if (!$agent || $agent->user_type != 2) {
                return $this->errorResponse('Unauthorized', 403);
            }

            $data = Merchant::where('agent_id', $agent->id)
                ->selectRaw('DATE(created_at) as date, COUNT(*) as total')
                ->groupBy('date')
                ->orderBy('date', 'ASC')
                ->get();

            return $this->successResponse($data, 'Growth data', 200);

        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }
}
