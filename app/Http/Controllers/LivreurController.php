<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LivreurController extends Controller
{
    public function dashboard()
    {
        $commandesDisponibles = Order::where('status', 'pending')
            ->whereNull('livreur_id')
            ->latest()
            ->get();

        $mesCommandes = Order::where('livreur_id', Auth::id())
            ->latest()
            ->get();

        return view('livreur.dashboard', compact('commandesDisponibles', 'mesCommandes'));
    }

    // Le livreur accepte une commande
    public function accepter(Order $order)
    {
        if ($order->status !== 'pending' || $order->livreur_id !== null) {
            return back()->with('error', 'Cette commande n\'est plus disponible.');
        }

        $order->update([
            'livreur_id' => Auth::id(),
            'status' => 'accepted',
        ]);

        return back()->with('success', 'Commande acceptée.');
    }

    // Le livreur refuse une commande (elle reste disponible pour un autre livreur)
    public function refuser(Order $order)
    {
        return back()->with('success', 'Commande refusée.');
    }

    // Marquer une commande comme en cours puis comme livrée
    public function changerStatut(Request $request, Order $order)
    {
        $request->validate([
            'status' => ['required', 'in:in_progress,delivered,cancelled'],
        ]);

        if ($order->livreur_id !== Auth::id()) {
            abort(403);
        }

        $order->update(['status' => $request->input('status')]);

        return back()->with('success', 'Statut mis à jour.');
    }

    // Mise à jour de la position GPS du livreur (appelé en AJAX depuis le navigateur)
    public function mettreAJourPosition(Request $request)
    {
        $validated = $request->validate([
            'lat' => ['required', 'numeric'],
            'lng' => ['required', 'numeric'],
        ]);

        Auth::user()->update([
            'current_lat' => $validated['lat'],
            'current_lng' => $validated['lng'],
            'last_seen_at' => now(),
            'is_online' => true,
        ]);

        return response()->json(['ok' => true]);
    }

    // Basculer en ligne / hors ligne manuellement
    public function toggleEnLigne(Request $request)
    {
        $user = Auth::user();
        $user->update(['is_online' => ! $user->is_online]);

        return back();
    }
}
