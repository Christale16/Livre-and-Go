@extends('layouts.app')

@section('title', 'Administration')

@section('content')
<div class="mb-4">
    <h3 class="mb-1"><i class="bi bi-speedometer2 text-primary"></i> Tableau de bord administrateur</h3>
    <p class="text-secondary mb-0">Vue d'ensemble de la plateforme.</p>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-primary fs-4"><i class="bi bi-people-fill"></i></div>
                <h2 class="mt-2 mb-0">{{ $stats['clients'] }}</h2>
                <p class="text-secondary mb-0">Clients</p>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-success fs-4"><i class="bi bi-bicycle"></i></div>
                <h2 class="mt-2 mb-0">{{ $stats['livreurs'] }}</h2>
                <p class="text-secondary mb-0">Livreurs ({{ $stats['livreurs_en_ligne'] }} en ligne)</p>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-info fs-4"><i class="bi bi-box-seam"></i></div>
                <h2 class="mt-2 mb-0">{{ $stats['commandes_total'] }}</h2>
                <p class="text-secondary mb-0">Commandes totales</p>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-warning fs-4"><i class="bi bi-hourglass-split"></i></div>
                <h2 class="mt-2 mb-0">{{ $stats['commandes_en_attente'] }}</h2>
                <p class="text-secondary mb-0">En attente</p>
            </div>
        </div>
    </div>
</div>

<div class="d-flex flex-wrap gap-2 mb-4">
    <a href="{{ route('admin.utilisateurs') }}" class="btn btn-primary"><i class="bi bi-people"></i> Gérer les utilisateurs</a>
    <a href="{{ route('admin.commandes') }}" class="btn btn-outline-primary"><i class="bi bi-box-seam"></i> Voir les commandes</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="bi bi-clock-history"></i> Dernières commandes</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr><th>#</th><th>Client</th><th>Livreur</th><th>Statut</th><th>Créée le</th></tr>
                </thead>
                <tbody>
                    @forelse($dernieres_commandes as $order)
                        @php
                            $statusClasses = ['pending'=>'text-bg-warning','accepted'=>'text-bg-primary','in_progress'=>'text-bg-info','delivered'=>'text-bg-success','refused'=>'text-bg-danger','cancelled'=>'text-bg-secondary'];
                            $statusLabels = ['pending'=>'En attente','accepted'=>'Acceptée','in_progress'=>'En cours','delivered'=>'Livrée','refused'=>'Refusée','cancelled'=>'Annulée'];
                        @endphp
                        <tr>
                            <td class="fw-semibold">{{ $order->id }}</td>
                            <td>{{ $order->client->name }}</td>
                            <td>{{ $order->livreur->name ?? '—' }}</td>
                            <td><span class="badge {{ $statusClasses[$order->status] ?? 'text-bg-secondary' }}">{{ $statusLabels[$order->status] ?? $order->status }}</span></td>
                            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-secondary py-4">Aucune commande.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
