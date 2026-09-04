@extends('layouts.app')

@section('title', 'Toutes les commandes')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h3 class="mb-1"><i class="bi bi-box-seam text-primary"></i> Toutes les commandes</h3>
        <p class="text-secondary mb-0">Consultez l'activité des livraisons.</p>
    </div>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Retour</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr><th>#</th><th>Client</th><th>Livreur</th><th>Retrait</th><th>Livraison</th><th>Statut</th><th>Paiement</th><th>Créée le</th></tr>
                </thead>
                <tbody>
                    @forelse($commandes as $order)
                        @php
                            $statusClasses = ['pending'=>'text-bg-warning','accepted'=>'text-bg-primary','in_progress'=>'text-bg-info','delivered'=>'text-bg-success','refused'=>'text-bg-danger','cancelled'=>'text-bg-secondary'];
                            $statusLabels = ['pending'=>'En attente','accepted'=>'Acceptée','in_progress'=>'En cours','delivered'=>'Livrée','refused'=>'Refusée','cancelled'=>'Annulée'];
                            $paymentLabels = ['mtn_momo'=>'MTN Mobile Money','moov_celtiis'=>'Celtiis Cash','bank_transfer'=>'Virement bancaire'];
                            $paymentStatusClasses = ['non_paye'=>'text-bg-warning','paye'=>'text-bg-success'];
                            $paymentStatusLabels = ['non_paye'=>'Non payé','paye'=>'Payé'];
                        @endphp
                        <tr>
                            <td class="fw-semibold">{{ $order->id }}</td>
                            <td>{{ $order->client->name }}</td>
                            <td>{{ $order->livreur->name ?? '—' }}</td>
                            <td>{{ $order->pickup_address }}</td>
                            <td>{{ $order->delivery_address }}</td>
                            <td><span class="badge {{ $statusClasses[$order->status] ?? 'text-bg-secondary' }}">{{ $statusLabels[$order->status] ?? $order->status }}</span></td>
                            <td>
                                {{ $paymentLabels[$order->payment_method] ?? '—' }}
                                @if($order->payment_method)
                                    <span class="badge {{ $paymentStatusClasses[$order->payment_status] ?? 'text-bg-secondary' }}">{{ $paymentStatusLabels[$order->payment_status] ?? $order->payment_status }}</span>
                                @endif
                            </td>
                            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center text-secondary py-4">Aucune commande.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($commandes->hasPages())
        <div class="card-footer bg-white">{{ $commandes->links() }}</div>
    @endif
</div>
@endsection
