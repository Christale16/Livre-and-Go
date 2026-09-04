<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors([
                'email' => 'Identifiants incorrects.',
            ])->onlyInput('email');
        }

        $request->session()->regenerate();

        $user = Auth::user();

        if ($user->isLivreur()) {
            $user->update(['is_online' => true, 'last_seen_at' => now()]);
        }

        return match ($user->role) {
            'client' => redirect()->intended(route('client.dashboard')),
            'livreur' => redirect()->intended(route('livreur.dashboard')),
            'admin' => redirect()->intended(route('admin.dashboard')),
            default => redirect('/'),
        };
    }

    public function logout(Request $request)
    {
        if (Auth::check() && Auth::user()->isLivreur()) {
            Auth::user()->update(['is_online' => false]);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
