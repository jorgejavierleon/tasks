<?php

namespace App\Http\Controllers;

use App\User;

class UsersController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * @return array
     */
    public function index()
    {
        $users = User::all();
        return response()->json([
            'data' => $users->toArray()
        ], 200);
    }
}