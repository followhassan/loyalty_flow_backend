<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        // if (is_null($this->user) || !$this->user->can('admin.user.view')) {
        //         abort(403, 'Sorry !! You are Unauthorized.');
        // }
        $data['title'] = 'User List';
        $data['users'] = User::where('user_type', 0)->orderBy('id', 'desc')->get();

        return view('admin.user.index', $data);
    }

    public function update(Request $request)
    {
        try {

            $user = User::findOrFail($request->id);

            $user->name   = $request->name;
            $user->email  = $request->email;
            $user->phone  = $request->phone;
            $user->status = $request->status;

            $user->save();

            return back()->with('success', 'User updated successfully');

        } catch (\Exception $e) {

            \Log::error('User update failed: ' . $e->getMessage());

            return back()->with('error', 'Something went wrong. Please try again.');
        }
    }
}
