<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\ApiResponseTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerProfileController extends Controller
{
    use ApiResponseTrait;

    public function updateProfile(Request $request)
    {
        try {

            $userDetails = Auth::user();

            $user =  User::find($userDetails->id);

            if (!$user) {
                return $this->errorResponse('Unauthorized', 401);
            }

            // Only customer allowed
            if ($user->user_type != 0) {
                return $this->errorResponse('Only customers can update this profile', 403);
            }

            $request->validate([
                'name'          => 'nullable|string|max:255',
                'phone'         => 'nullable|unique:users,phone,' . $user->id,
                'country_code'  => 'nullable|string|max:10',
                'date_of_birth' => 'nullable|date',
                'image'         => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
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

            // Profile Image Upload
            if ($request->hasFile('image')) {

                $file = $request->file('image');
                $filename = time().'_'.$file->getClientOriginalName();

                $file->move(public_path('uploads/profile'), $filename);

                $user->image = 'uploads/profile/'.$filename;
            }

            $user->save();

            $data = [
                'id'            => $user->id,
                'user_id'       => $user->user_id,
                'name'          => $user->name,
                'email'         => $user->email,
                'phone'         => $user->phone,
                'country_code'  => $user->country_code,
                'date_of_birth' => $user->date_of_birth,
                'image'         => $user->image ? url($user->image) : null,
            ];

            return $this->successResponse($data, 'Profile updated successfully', 200);

        } catch (\Exception $e) {

            return $this->errorResponse($e->getMessage(), 500);

        }
    }
}
