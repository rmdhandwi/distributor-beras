<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

class AuthenticatedController extends Controller
{
    /**
     * Display the login view.
     */
    // public function create(): Response
    // {
    //     return Inertia::render('Auth/Login', [
    //         'canResetPassword' => Route::has('password.request'),
    //         'status' => session('status'),
    //     ]);
    // }

    /**
     * Handle an incoming authentication request.
     */
    // public function store(LoginRequest $request): RedirectResponse
    // {
    //     $request->authenticate();

    //     $request->session()->regenerate();

    //     return redirect()->intended(route('dashboard', absolute: false));
    // }
    public function submitLogin(Request $req)
    {
        $loginForm = $req->validate([
            'username' => 'required|exists:users,username',
            'password' => 'required',
        ],[
            'username.required' => 'Harap isi username',
            'username.exists' => $req->username.' tidak terdaftar',
            'password.required' => 'Harap isi password',
        ]);

        if(Auth::attempt($loginForm))
        {
            $notification = [
                'notif_status' => 'success',
                'notif_message' => 'Selamat Datang '.$req->username,
            ];

            $req->session()->regenerate();

            switch(auth()->guard()->user()->role)
            {
                case 'Admin' : return redirect()->route('admin.dashboard')->with($notification);
                break;
            }

        }
        else
        {
            $notification = [
                'notif_status' => 'error',
                'notif_message' => 'Login Gagal',
            ];

            return redirect()->back()->with($notification)->withErrors([
                'password' => 'Password Salah'
            ]);
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
