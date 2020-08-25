<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Task;
use App\QuickResponse;
use App\TaskTemplate;
use App\TemplateTask;
use App\User;
use Illuminate\Support\Facades\Auth;
use PayPal\Api\Template;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function assignUserRoles(Request $request) {
        $user_id = $request['user_id'];
        $trade = $request['trade'] == 'true';
        $agent = $request['agent'] == 'true';
        $admin = $request['admin'] == 'true';

        if(!Auth::user()->admin) {
            return QuickResponse::warning("Only admins can assign rules to other users.");
        }

        if(!User::where('id', $user_id)->exists()) {
            return QuickResponse::warning("User not found.");
        }

        $user = User::where('id', $user_id)->first();
        $user->trade = $trade;
        $user->agent = $agent;
        $user->admin = $admin;
        $user->save();

        return QuickResponse::success('Success.');
    }

}
