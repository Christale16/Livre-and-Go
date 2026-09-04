@extends('layouts.app')

@section('title', 'Inscription')

@section('content')
<div class="row justify-content-center">
    <div class="col-sm-10 col-md-8 col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-primary text-white text-center py-3">
                <h4 class="mb-0">
                    <i class="bi bi-person-plus-fill"></i>
                    Inscription {{ $role === 'livreur' ? 'Livreur' : 'Client' }}
                </h4>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <input type="hidden" name="role" value="{{ $role }}">

                    <div class="mb-3">
                        <label class="form-label fw-semibold" for="name">Nom complet</label>
                        <input id="name" type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name') }}" required autofocus autocomplete="name">
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold" for="email">Adresse e-mail</label>
                        <input id="email" type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email') }}" required autocomplete="email">
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold" for="phone">Téléphone</label>
                        <input id="phone" type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror"
                               value="{{ old('phone') }}" required autocomplete="tel">
                        @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    @if($role === 'livreur')
                        <div class="mb-3">
                            <label class="form-label fw-semibold" for="vehicle_type">Type de véhicule</label>
                            <select id="vehicle_type" name="vehicle_type" class="form-select @error('vehicle_type') is-invalid @enderror" required>
                                <option value="">-- Choisir --</option>
                                <option value="velo" @selected(old('vehicle_type') === 'velo')>Vélo</option>
                                <option value="moto" @selected(old('vehicle_type') === 'moto')>Moto</option>
                                <option value="voiture" @selected(old('vehicle_type') === 'voiture')>Voiture</option>
                                <option value="camionnette" @selected(old('vehicle_type') === 'camionnette')>Camionnette</option>
                            </select>
                            @error('vehicle_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    @endif

                    <div class="mb-3">
                        <label class="form-label fw-semibold" for="password">Mot de passe</label>
                        <input id="password" type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                               required autocomplete="new-password">
                        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold" for="password_confirmation">Confirmer le mot de passe</label>
                        <input id="password_confirmation" type="password" name="password_confirmation" class="form-control"
                               required autocomplete="new-password">
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-person-check-fill"></i> S'inscrire
                    </button>
                </form>
                <hr class="my-4">
                <p class="text-center text-secondary mb-0">
                    Déjà inscrit ? <a href="{{ route('login') }}" class="link-primary">Se connecter</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
