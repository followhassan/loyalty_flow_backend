<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MerchantController extends Controller
{
    public function index()
    {
        // if (is_null($this->user) || !$this->user->can('admin.user.view')) {
        //         abort(403, 'Sorry !! You are Unauthorized.');
        // }
        $data['title'] = 'Merchant List';

        return view('admin.merchent.index', $data);
    }
}
