<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AgentController extends Controller
{
    public function index()
    {
        // if (is_null($this->user) || !$this->user->can('admin.user.view')) {
        //         abort(403, 'Sorry !! You are Unauthorized.');
        // }
        $data['title'] = 'Agent List';
        $data['agents'] = User::where('user_type', 2)
                        ->withCount('merchants')
                        ->with('merchants')
                        ->orderBy('id', 'desc')->get();

        return view('admin.agent.index', $data);
    }

    public function storeAgent(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|unique:users,email',
            'phone'        => 'nullable|string|max:20',
            'country_code' => 'nullable|string|max:5',
            'password'     => 'required|string|min:6|confirmed',
            'date_of_birth'=> 'nullable|date',
        ]);

        // 1. Prefix for Agent
        $prefix = 'A'; // Agent prefix

        // 2. Get last agent ID to increment
        $lastUser = User::where('user_type', 2)
                        ->orderBy('id', 'desc')
                        ->first();

        if ($lastUser && $lastUser->user_id) {
            $number = (int) substr($lastUser->user_id, 1) + 1;
        } else {
            $number = 1001;
        }

        $userId = $prefix . $number;

        // 3. Handle referral
        $referrerId = null;
        if ($request->filled('referral_code')) {
            $referrer = User::where('referral_code', $request->referral_code)->first();
            if ($referrer) {
                $referrerId = $referrer->id;
            }
        }

        // 4. Generate OTP if needed
        $otp = rand(100000, 999999);

        // 5. Create user record
        $user = new User();
        $user->user_id           = $userId;
        $user->name              = $request->name;
        $user->email             = $request->email;
        $user->email_verified_at = now();
        $user->phone             = $request->phone;
        $user->country_code      = $request->country_code;
        $user->password          = Hash::make($request->password);
        $user->user_type         = 2; // Agent
        $user->verification_code = $otp;
        $user->is_verified       = 1;
        $user->status            = 1;
        $user->date_of_birth     = $request->date_of_birth;
        $user->referral_code     = $this->generateReferralCode($prefix);
        $user->referred_by       = $referrerId;
        $user->save();

        // 6. Create agent record
        Agent::create([
            'user_id'       => $user->id,
            'referral_code' => $user->referral_code,
        ]);

        return redirect()->back()->with('success', 'Agent created successfully.');
    }

    public function show($id)
    {
        $user = User::findOrFail($id);

        return response()->json([
            'user' => $user
        ]);
    }

    public function update(Request $request)
    {
        // dd($request->all());
        // Validate input
        $request->validate([
            'id'            => 'required|exists:users,id',
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|max:255',
            'status'        => 'required|in:0,1',
        ]);

        // Get the user and merchant
        $user = User::findOrFail($request->id);


        // Update user
        $user->update([
            'name'  => $request->name,
            'email' => $request->email,
            'status'=> $request->status,
        ]);

        return back()->with('success', 'Agent profile updated successfully.');
    }

    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);

        // Toggle status: 1 -> 0, 0 -> 1
        $user->status = $user->status == 1 ? 0 : 1;
        $user->save();

        return redirect()->back()->with('success', 'Agent status updated successfully.');
    }

    private function generateReferralCode($prefix)
    {
        do {
            $code = $prefix . strtoupper(Str::random(6));
        } while(User::where('referral_code', $code)->exists());

        return $code;
    }

    public function merchantList($id)
    {
        // if (is_null($this->user) || !$this->user->can('admin.user.view')) {
        //         abort(403, 'Sorry !! You are Unauthorized.');
        // }
        $data['title'] = 'Agent Merchant List';

        // 👉 Get single agent with merchants
        $agent = User::where('id', $id)
                    ->where('user_type', 2) // agent
                    ->with('merchants')
                    ->withCount('merchants')
                    ->firstOrFail();

        $data['agent'] = $agent;
        $data['merchants'] = $agent->merchants;
        return view('admin.agent.merchant', $data);
    }
}
