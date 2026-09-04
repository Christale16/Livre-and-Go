@extends('layouts.app')

@section('title', 'Nouvelle commande')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-primary text-white py-3">
                <h4 class="mb-0"><i class="bi bi-box-seam"></i> Créer une commande</h4>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('client.orders.store') }}">
                    @csrf

                    <h6 class="text-primary border-bottom pb-2 mb-3"><i class="bi bi-geo-alt"></i> Retrait</h6>
                    <div class="mb-3">
                        <label class="form-label fw-semibold" for="pickup_address">Quartier ou lieu de retrait</label>
                        <input id="pickup_address" type="text" name="pickup_address" class="form-control @error('pickup_address') is-invalid @enderror"
                               required value="{{ old('pickup_address') }}" placeholder="Ex. Cadjèhoun, Cotonou" autocomplete="address-line1">
                        @error('pickup_address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <div class="form-text">Écrivez simplement le nom du quartier ou du lieu. L'application trouve automatiquement la position.</div>
                    </div>

                    <h6 class="text-primary border-bottom pb-2 mb-3 mt-2"><i class="bi bi-geo-alt-fill"></i> Livraison</h6>
                    <div class="mb-3">
                        <label class="form-label fw-semibold" for="delivery_address">Quartier ou lieu de livraison</label>
                        <input id="delivery_address" type="text" name="delivery_address" class="form-control @error('delivery_address') is-invalid @enderror"
                               required value="{{ old('delivery_address') }}" placeholder="Ex. Fidjrossè, Cotonou" autocomplete="address-line1">
                        @error('delivery_address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <div class="form-text">Écrivez simplement le nom du quartier ou du lieu. L'application trouve automatiquement la position.</div>
                    </div>

                    <h6 class="text-primary border-bottom pb-2 mb-3 mt-2"><i class="bi bi-info-circle"></i> Informations du colis</h6>
                    <div class="mb-3">
                        <label class="form-label fw-semibold" for="description">Description du colis</label>
                        <textarea id="description" name="description" class="form-control" rows="3" placeholder="Décrivez brièvement le colis...">{{ old('description') }}</textarea>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold" for="price">Prix proposé (optionnel)</label>
                        <div class="input-group">
                            <input id="price" type="number" step="0.01" min="0" name="price" class="form-control" value="{{ old('price') }}">
                            <span class="input-group-text">FCFA</span>
                        </div>
                    </div>

                    <h6 class="text-primary border-bottom pb-2 mb-3 mt-2"><i class="bi bi-wallet2"></i> Moyen de paiement</h6>
                    <div class="mb-3">
                        @error('payment_method') <div class="text-danger small mb-2">{{ $message }}</div> @enderror

                        <div class="list-group">
                            <label class="list-group-item d-flex gap-3">
                                <input class="form-check-input flex-shrink-0" type="radio" name="payment_method" value="mtn_momo"
                                       {{ old('payment_method') == 'mtn_momo' ? 'checked' : '' }} required>
                                <span>
                                    <strong>{{ $paymentAccounts['mtn_momo']['label'] }}</strong>
                                    <span class="d-block text-secondary small">
                                        Envoyer au {{ $paymentAccounts['mtn_momo']['number'] }} ({{ $paymentAccounts['mtn_momo']['holder'] }})
                                    </span>
                                </span>
                            </label>
                            <label class="list-group-item d-flex gap-3">
                                <input class="form-check-input flex-shrink-0" type="radio" name="payment_method" value="moov_celtiis"
                                       {{ old('payment_method') == 'moov_celtiis' ? 'checked' : '' }} required>
                                <span>
                                    <strong>{{ $paymentAccounts['moov_celtiis']['label'] }}</strong>
                                    <span class="d-block text-secondary small">
                                        Envoyer au {{ $paymentAccounts['moov_celtiis']['number'] }} ({{ $paymentAccounts['moov_celtiis']['holder'] }})
                                    </span>
                                </span>
                            </label>
                            <label class="list-group-item d-flex gap-3">
                                <input class="form-check-input flex-shrink-0" type="radio" name="payment_method" value="bank_transfer"
                                       {{ old('payment_method') == 'bank_transfer' ? 'checked' : '' }} required>
                                <span>
                                    <strong>{{ $paymentAccounts['bank_transfer']['label'] }}</strong>
                                    <span class="d-block text-secondary small">
                                        {{ $paymentAccounts['bank_transfer']['bank_name'] }} — RIB : {{ $paymentAccounts['bank_transfer']['rib'] }}
                                        ({{ $paymentAccounts['bank_transfer']['holder'] }})
                                    </span>
                                </span>
                            </label>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold" for="payment_reference">Référence / numéro de transaction (optionnel)</label>
                        <input id="payment_reference" type="text" name="payment_reference" class="form-control @error('payment_reference') is-invalid @enderror"
                               value="{{ old('payment_reference') }}" placeholder="Ex. référence reçue après votre paiement">
                        @error('payment_reference') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <div class="form-text">Si vous avez déjà payé, indiquez ici la référence pour accélérer la confirmation.</div>
                    </div>

                    <div class="d-flex flex-column flex-sm-row gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1">
                            <i class="bi bi-send-fill"></i> Envoyer la commande
                        </button>
                        <a href="{{ route('client.dashboard') }}" class="btn btn-outline-secondary">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
