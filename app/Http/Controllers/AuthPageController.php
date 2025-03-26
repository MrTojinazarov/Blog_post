<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthPageController extends Controller
{
    public function RegisterPage()
    {
        return view('auth.register');
    }

    public function LoginPage()
    {
        return view('auth.login');
    }
}
