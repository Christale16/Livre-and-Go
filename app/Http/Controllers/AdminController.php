<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'clients' => User::where('role', 'client')->count(),
            'livreurs' => User::where('role', 'livreur')->count(),
            'livreurs_en_ligne' => User::where('role', 'livreur')->where('is_online', true)->count(),
            'commandes_total' => Order::count(),
            'commandes_en_attente' => Order::where('status', 'pending')->count(),
            'commandes_en_cours' => Order::where('status', 'in_progress')->count(),
            'commandes_livrees' => Order::where('status', 'delivered')->count(),
        ];

        $dernieres_commandes = Order::with(['client', 'livreur'])->latest()->take(20)->get();

        return view('admin.dashboard', compact('stats', 'dernieres_commandes'));
    }

    public function utilisateurs()
    {
        $utilisateurs = User::latest()->paginate(20);

        return view('admin.utilisateurs', compact('utilisateurs'));
    }

    public function commandes()
    {
        $commandes = Order::with(['client', 'livreur'])->latest()->paginate(20);

        return view('admin.commandes', compact('commandes'));
    }

    public function supprimerUtilisateur(User $user)
    {
        if ($user->isAdmin()) {
            return back()->with('error', 'Impossible de supprimer un administrateur.');
        }

        $user->delete();

        return back()->with('success', 'Utilisateur supprimé.');
    }
}
