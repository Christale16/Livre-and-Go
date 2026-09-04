<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisterController extends Controller
{
    // Formulaire d'inscription client
    public function showClientForm()
    {
        return view('auth.register', ['role' => 'client']);
    }

    // Formulaire d'inscription livreur
    public function showLivreurForm()
    {
        return view('auth.register', ['role' => 'livreur']);
    }

    public function register(Request $request)
    {
        $role = $request->input('role');

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:30'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:client,livreur'],
        ];

        if ($role === 'livreur') {
            $rules['vehicle_type'] = ['required', 'string', 'max:50'];
        }

        $validated = $request->validate($rules);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'vehicle_type' => $validated['vehicle_type'] ?? null,
        ]);

        Auth::login($user);

        return $this->redirectForRole($user);
    }

    public function redirectForRole(User $user)
    {
        return match ($user->role) {
            'client' => redirect()->route('client.dashboard'),
            'livreur' => redirect()->route('livreur.dashboard'),
            'admin' => redirect()->route('admin.dashboard'),
            default => redirect('/'),
        };
    }
}
