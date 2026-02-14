<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AgentController extends Controller
{
    public function index()
    {
        // if (is_null($this->user) || !$this->user->can('admin.user.view')) {
        //         abort(403, 'Sorry !! You are Unauthorized.');
        // }
        $data['title'] = 'Agent List';

        return view('admin.agent.index', $data);
    }
}
