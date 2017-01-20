<?php

namespace App\Http\Controllers;

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
        return [
            ['firstname' => 'Pedaro'],
            ['firstname' => 'María'],
        ];
    }
}