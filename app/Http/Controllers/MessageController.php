<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function index(Order $order)
    {
        $user = Auth::user();

        if ($order->client_id !== $user->id && $order->livreur_id !== $user->id && ! $user->isAdmin()) {
            abort(403);
        }

        $messages = $order->messages()->with('sender')->orderBy('created_at')->get();

        return view('messages.index', compact('order', 'messages'));
    }

    public function store(Request $request, Order $order)
    {
        $user = Auth::user();

        if ($order->client_id !== $user->id && $order->livreur_id !== $user->id) {
            abort(403);
        }

        $validated = $request->validate([
            'content' => ['required', 'string', 'max:2000'],
        ]);

        $receiverId = $order->client_id === $user->id ? $order->livreur_id : $order->client_id;

        if (! $receiverId) {
            return back()->with('error', 'Aucun livreur assigné à cette commande pour le moment.');
        }

        Message::create([
            'order_id' => $order->id,
            'sender_id' => $user->id,
            'receiver_id' => $receiverId,
            'content' => $validated['content'],
        ]);

        return back();
    }

    // Récupère les nouveaux messages en JSON (pour rafraîchissement AJAX)
    public function fetch(Order $order)
    {
        $user = Auth::user();

        if ($order->client_id !== $user->id && $order->livreur_id !== $user->id && ! $user->isAdmin()) {
            abort(403);
        }

        $messages = $order->messages()->with('sender')->orderBy('created_at')->get()
            ->map(fn ($m) => [
                'id' => $m->id,
                'content' => $m->content,
                'sender_id' => $m->sender_id,
                'sender_name' => $m->sender?->name ?? 'Utilisateur',
                'created_at' => optional($m->created_at)->format('H:i'),
            ]);

        return response()->json($messages);
    }
}
