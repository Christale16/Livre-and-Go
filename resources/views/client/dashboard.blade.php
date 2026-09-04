@extends('layouts.app')

@section('title', 'Tableau de bord client')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-4">
    <div>
        <h3 class="mb-1"><i class="bi bi-speedometer2 text-primary"></i> Mes commandes</h3>
        <p class="text-secondary mb-0">Suivez vos livraisons et les livreurs disponibles.</p>
    </div>
    <a href="{{ route('client.orders.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Nouvelle commande
    </a>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-primary text-white">
        <i class="bi bi-geo-alt-fill"></i> Livreurs en ligne
    </div>
    <div class="card-body p-2 p-md-3">
        <div id="map"></div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0"><i class="bi bi-box-seam text-primary"></i> Historique des commandes</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th><th>Retrait</th><th>Livraison</th><th>Statut</th><th>Livreur</th><th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        @php
                            $statusClasses = [
                                'pending' => 'text-bg-warning',
                                'accepted' => 'text-bg-primary',
                                'in_progress' => 'text-bg-info',
                                'delivered' => 'text-bg-success',
                                'refused' => 'text-bg-danger',
                                'cancelled' => 'text-bg-secondary',
                            ];
                            $statusLabels = [
                                'pending' => 'En attente',
                                'accepted' => 'Acceptée',
                                'in_progress' => 'En cours',
                                'delivered' => 'Livrée',
                                'refused' => 'Refusée',
                                'cancelled' => 'Annulée',
                            ];
                        @endphp
                        <tr>
                            <td class="fw-semibold">{{ $order->id }}</td>
                            <td>{{ $order->pickup_address }}</td>
                            <td>{{ $order->delivery_address }}</td>
                            <td><span class="badge {{ $statusClasses[$order->status] ?? 'text-bg-secondary' }}">{{ $statusLabels[$order->status] ?? $order->status }}</span></td>
                            <td>{{ $order->livreur->name ?? '—' }}</td>
                            <td>
                                <div class="d-flex flex-wrap gap-1">
                                    <a href="{{ route('client.orders.show', $order) }}" class="btn btn-sm btn-outline-primary">Voir</a>
                                    @if($order->livreur_id)
                                        <a href="{{ route('orders.messages.index', $order) }}" class="btn btn-sm btn-outline-secondary">Messages</a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-secondary py-4">Aucune commande pour le moment.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const map = L.map('map').setView([6.37, 2.43], 12);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    let markers = {};

    function rafraichirLivreurs() {
        fetch("{{ route('client.livreurs.json') }}")
            .then(r => r.json())
            .then(livreurs => {
                Object.keys(markers).forEach(id => {
                    if (!livreurs.find(l => l.id == id)) {
                        map.removeLayer(markers[id]);
                        delete markers[id];
                    }
                });

                livreurs.forEach(l => {
                    const latlng = [Number(l.current_lat), Number(l.current_lng)];
                    if (markers[l.id]) {
                        markers[l.id].setLatLng(latlng);
                    } else {
                        markers[l.id] = L.marker(latlng).addTo(map)
                            .bindPopup(`<strong>${escapeHtml(l.name)}</strong><br>${escapeHtml(l.vehicle_type ?? '')}`);
                    }
                });
            })
            .catch(() => {});
    }

    function escapeHtml(value) {
        const div = document.createElement('div');
        div.textContent = value ?? '';
        return div.innerHTML;
    }

    rafraichirLivreurs();
    setInterval(rafraichirLivreurs, 5000);
</script>
@endpush
