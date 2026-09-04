@extends('layouts.app')

@section('title', 'Tableau de bord livreur')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-4">
    <div>
        <h3 class="mb-1"><i class="bi bi-bicycle text-primary"></i> Espace livreur</h3>
        <p class="text-secondary mb-0">Gérez vos commandes et votre disponibilité.</p>
    </div>
    <form method="POST" action="{{ route('livreur.toggle.online') }}">
        @csrf
        <button class="btn {{ auth()->user()->is_online ? 'btn-success' : 'btn-secondary' }}" type="submit">
            <i class="bi {{ auth()->user()->is_online ? 'bi-circle-fill' : 'bi-circle' }}"></i>
            {{ auth()->user()->is_online ? 'En ligne' : 'Hors ligne' }}
        </button>
    </form>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-warning">
        <h5 class="mb-0"><i class="bi bi-bell-fill"></i> Commandes disponibles</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr><th>#</th><th>Retrait</th><th>Livraison</th><th>Client</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    @forelse($commandesDisponibles as $order)
                        <tr>
                            <td class="fw-semibold">{{ $order->id }}</td>
                            <td>{{ $order->pickup_address }}</td>
                            <td>{{ $order->delivery_address }}</td>
                            <td>{{ $order->client->name }}</td>
                            <td>
                                <div class="d-flex flex-wrap gap-1">
                                    <form method="POST" action="{{ route('livreur.orders.accepter', $order) }}" class="d-inline">
                                        @csrf
                                        <button class="btn btn-sm btn-success" type="submit"><i class="bi bi-check-lg"></i> Accepter</button>
                                    </form>
                                    <form method="POST" action="{{ route('livreur.orders.refuser', $order) }}" class="d-inline">
                                        @csrf
                                        <button class="btn btn-sm btn-outline-danger" type="submit"><i class="bi bi-x-lg"></i> Refuser</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-secondary py-4">Aucune commande disponible pour le moment.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="bi bi-box-seam"></i> Mes commandes</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr><th>#</th><th>Retrait</th><th>Livraison</th><th>Statut</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    @forelse($mesCommandes as $order)
                        @php
                            $statusClasses = [
                                'accepted' => 'text-bg-primary',
                                'in_progress' => 'text-bg-info',
                                'delivered' => 'text-bg-success',
                                'cancelled' => 'text-bg-secondary',
                            ];
                            $statusLabels = [
                                'accepted' => 'Acceptée',
                                'in_progress' => 'En cours',
                                'delivered' => 'Livrée',
                                'cancelled' => 'Annulée',
                            ];
                        @endphp
                        <tr>
                            <td class="fw-semibold">{{ $order->id }}</td>
                            <td>{{ $order->pickup_address }}</td>
                            <td>{{ $order->delivery_address }}</td>
                            <td><span class="badge {{ $statusClasses[$order->status] ?? 'text-bg-secondary' }}">{{ $statusLabels[$order->status] ?? $order->status }}</span></td>
                            <td>
                                <div class="d-flex flex-wrap gap-1">
                                    <a href="{{ route('orders.messages.index', $order) }}" class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-chat-dots"></i> Messages
                                    </a>
                                    @if($order->status === 'accepted')
                                        <form method="POST" action="{{ route('livreur.orders.statut', $order) }}" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="status" value="in_progress">
                                            <button class="btn btn-sm btn-primary" type="submit"><i class="bi bi-play-fill"></i> Démarrer</button>
                                        </form>
                                    @elseif($order->status === 'in_progress')
                                        <form method="POST" action="{{ route('livreur.orders.statut', $order) }}" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="status" value="delivered">
                                            <button class="btn btn-sm btn-success" type="submit"><i class="bi bi-check-circle"></i> Marquer livré</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-secondary py-4">Aucune commande en cours.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function envoyerPosition(position) {
        fetch("{{ route('livreur.position.update') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': window.CSRF_TOKEN
            },
            body: JSON.stringify({
                lat: position.coords.latitude,
                lng: position.coords.longitude
            })
        }).catch(() => {});
    }

    if (navigator.geolocation) {
        navigator.geolocation.watchPosition(
            envoyerPosition,
            (err) => console.warn('Géolocalisation indisponible :', err.message),
            { enableHighAccuracy: true, maximumAge: 10000, timeout: 10000 }
        );
    }
</script>
@endpush
