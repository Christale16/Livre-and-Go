<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class OrderController extends Controller
{
    public function create()
    {
        return view('client.order-create', [
            'paymentAccounts' => config('payment'),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pickup_address' => ['required', 'string', 'max:255'],
            'delivery_address' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['nullable', 'numeric'],
            'payment_method' => ['required', 'in:mtn_momo,moov_celtiis,bank_transfer'],
            'payment_reference' => ['nullable', 'string', 'max:255'],
        ]);

        // Le client saisit seulement le quartier/lieu. L'application
        // cherche automatiquement ses coordonnées GPS.
        $pickup = $this->geocode($validated['pickup_address']);
        $delivery = $this->geocode($validated['delivery_address']);

        if (!$pickup) {
            return back()->withInput()->withErrors([
                'pickup_address' => 'Quartier ou lieu de retrait introuvable. Essayez avec le quartier et la ville (ex. Cadjèhoun, Cotonou).',
            ]);
        }

        if (!$delivery) {
            return back()->withInput()->withErrors([
                'delivery_address' => 'Quartier ou lieu de livraison introuvable. Essayez avec le quartier et la ville (ex. Fidjrossè, Cotonou).',
            ]);
        }

        $validated['pickup_lat'] = $pickup['lat'];
        $validated['pickup_lng'] = $pickup['lng'];
        $validated['delivery_lat'] = $delivery['lat'];
        $validated['delivery_lng'] = $delivery['lng'];
        $validated['client_id'] = Auth::id();
        $validated['status'] = 'pending';
        $validated['payment_status'] = 'non_paye';

        $order = Order::create($validated);

        return redirect()->route('client.dashboard')->with('success', 'Commande créée, en attente d\'un livreur.');
    }

    /**
     * Transforme un nom de quartier/lieu en latitude + longitude.
     * Le client ne voit jamais ces coordonnées.
     */
    private function geocode(string $address): ?array
    {
        $query = trim($address) . ', Bénin';

        $response = Http::timeout(8)
            ->withHeaders([
                'User-Agent' => config('services.nominatim.user_agent', 'LivreAndGo/1.0'),
            ])
            ->get(config('services.nominatim.url', 'https://nominatim.openstreetmap.org/search'), [
                'q' => $query,
                'format' => 'jsonv2',
                'limit' => 1,
                'countrycodes' => 'bj',
            ]);

        if (!$response->successful() || empty($response->json())) {
            return null;
        }

        $place = $response->json()[0];

        if (!isset($place['lat'], $place['lon'])) {
            return null;
        }

        return [
            'lat' => (float) $place['lat'],
            'lng' => (float) $place['lon'],
        ];
    }

    public function show(Order $order)
    {
        $user = Auth::user();

        if ($order->client_id !== $user->id && $order->livreur_id !== $user->id && ! $user->isAdmin()) {
            abort(403);
        }

        return view('client.order-show', compact('order'));
    }
}
