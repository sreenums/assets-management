<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Generate the login form view.
     *
     * @return View
     */
    public function loginForm()
    {
        return view('login.user-login');
    }

    
    /**
     * Login attempt
     *
     * @param Request $request form request credentials
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            return redirect()->intended('/assets');
        }

        return redirect()->back()->withInput($request->only('email'))->withErrors(['loginError' => 'Invalid email Id or password']);
    }

    /**
     * Logut the user and redirect to login page
     * 
     * @param Request $request logout request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'You have been logged out.');
    }
}
