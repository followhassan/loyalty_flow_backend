<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    public function index()
    {
        // if (is_null($this->user) || !$this->user->can('admin.user.view')) {
        //         abort(403, 'Sorry !! You are Unauthorized.');
        // }
        $data['title'] = 'Promotion List';

        return view('admin.promotions.index', $data);
    }
}
