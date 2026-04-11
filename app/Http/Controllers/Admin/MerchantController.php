<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class MerchantController extends Controller
{
    public function index()
    {
        // if (is_null($this->user) || !$this->user->can('admin.user.view')) {
        //         abort(403, 'Sorry !! You are Unauthorized.');
        // }
        $data['title'] = 'Merchant List';
        
        $data['merchents'] = User::with('merchant')
                            ->withCount('merchantTransactions')
                            ->withSum('merchantTransactions', 'amount')
                            ->where('user_type', 1)
                            ->orderBy('id', 'desc')->get();
        return view('admin.merchent.index', $data);
    }

    public function show($id)
    {
        $user = User::with('merchant')->findOrFail($id);

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
            'business_name' => 'required|string|max:255',
            'address'       => 'required|string|max:255',
            'status'        => 'required|in:0,1',
        ]);

        // Get the user and merchant
        $user = User::findOrFail($request->id);
        $merchant = $user->merchant;

        if (!$merchant) {
            return back()->with('error', 'Merchant profile not found.');
        }

        // Update user
        $user->update([
            'name'  => $request->name,
            'email' => $request->email,
            'status'=> $request->status,
        ]);

        // Update merchant
        $merchant->update([
            'business_name' => $request->business_name,
            'address'       => $request->address,
        ]);

        return back()->with('success', 'Merchant profile updated successfully.');
    }

    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);

        // Toggle status: 1 -> 0, 0 -> 1
        $user->status = $user->status == 1 ? 0 : 1;
        $user->save();

        return redirect()->back()->with('success', 'Merchant status updated successfully.');
    }

    public function customers($id)
    {
        $title = 'Merchant Customers List';
        $merchant = User::findOrFail($id);

        $customers = User::whereIn('id', function ($query) use ($id) {
            $query->select('customer_id')
                ->from('transactions')
                ->where('merchant_id', $id);
        })->get();

        return view('admin.merchent.customers', compact('merchant', 'customers', 'title'));
    }
}
