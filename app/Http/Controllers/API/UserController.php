<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;

class UserController extends Controller
{
    public function index(Request $request) {
        $user = Auth::user();
        // if(!$user->tokenCan('admin')) abort(403);
        return $user;
    }
}
