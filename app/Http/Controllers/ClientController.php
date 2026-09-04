<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    public function dashboard()
    {
        $orders = Order::where('client_id', Auth::id())
            ->latest()
            ->get();

        return view('client.dashboard', compact('orders'));
    }

    // Liste des livreurs actuellement en ligne, avec leur position (pour la carte)
    public function livreursEnLigne()
    {
        $livreurs = User::where('role', 'livreur')
            ->where('is_online', true)
            ->whereNotNull('current_lat')
            ->whereNotNull('current_lng')
            ->get(['id', 'name', 'vehicle_type', 'current_lat', 'current_lng', 'last_seen_at']);

        return response()->json($livreurs);
    }

    public function carte()
    {
        return view('client.carte');
    }
}
