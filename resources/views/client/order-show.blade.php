@extends('layouts.app')

@section('title', 'Détail commande #' . $order->id)

@section('content')
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
    $paymentLabels = [
        'mtn_momo' => 'MTN Mobile Money',
        'moov_celtiis' => 'Celtiis Cash',
        'bank_transfer' => 'Virement bancaire',
    ];
    $paymentStatusClasses = [
        'non_paye' => 'text-bg-warning',
        'paye' => 'text-bg-success',
    ];
    $paymentStatusLabels = [
        'non_paye' => 'Non payé',
        'paye' => 'Payé',
    ];
@endphp

<div class="card border-0 shadow-sm">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h4 class="mb-0"><i class="bi bi-box-seam"></i> Commande #{{ $order->id }}</h4>
        <span class="badge {{ $statusClasses[$order->status] ?? 'text-bg-secondary' }}">
            {{ $statusLabels[$order->status] ?? $order->status }}
        </span>
    </div>
    <div class="card-body p-4">
        <div class="row g-4">
            <div class="col-md-6">
                <div class="card h-100 bg-light border-0">
                    <div class="card-body">
                        <h6 class="text-primary"><i class="bi bi-geo-alt"></i> Retrait</h6>
                        <p class="mb-0">{{ $order->pickup_address }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card h-100 bg-light border-0">
                    <div class="card-body">
                        <h6 class="text-success"><i class="bi bi-geo-alt-fill"></i> Livraison</h6>
                        <p class="mb-0">{{ $order->delivery_address }}</p>
                    </div>
                </div>
            </div>
        </div>

        <dl class="row mt-4 mb-0">
            <dt class="col-sm-4">Description</dt>
            <dd class="col-sm-8">{{ $order->description ?? '—' }}</dd>
            <dt class="col-sm-4">Livreur assigné</dt>
            <dd class="col-sm-8">{{ $order->livreur->name ?? 'En attente d’un livreur' }}</dd>
            <dt class="col-sm-4">Prix proposé</dt>
            <dd class="col-sm-8">{{ $order->price !== null ? number_format($order->price, 2, ',', ' ') . ' FCFA' : '—' }}</dd>
            <dt class="col-sm-4">Moyen de paiement</dt>
            <dd class="col-sm-8">{{ $paymentLabels[$order->payment_method] ?? '—' }}</dd>
            <dt class="col-sm-4">Statut du paiement</dt>
            <dd class="col-sm-8">
                <span class="badge {{ $paymentStatusClasses[$order->payment_status] ?? 'text-bg-secondary' }}">
                    {{ $paymentStatusLabels[$order->payment_status] ?? $order->payment_status }}
                </span>
            </dd>
            @if($order->payment_reference)
                <dt class="col-sm-4">Référence de paiement</dt>
                <dd class="col-sm-8">{{ $order->payment_reference }}</dd>
            @endif
        </dl>

        <div class="d-flex flex-column flex-sm-row gap-2 mt-4">
            @if($order->livreur_id)
                <a href="{{ route('orders.messages.index', $order) }}" class="btn btn-primary">
                    <i class="bi bi-chat-dots"></i> Ouvrir la messagerie
                </a>
            @endif
            <a href="{{ route('client.dashboard') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Retour
            </a>
        </div>
    </div>
</div>
@endsection
