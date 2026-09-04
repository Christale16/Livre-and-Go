@extends('layouts.app')

@section('title', 'Connexion')

@section('content')
<div class="row justify-content-center">
    <div class="col-sm-10 col-md-6 col-lg-5">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-primary text-white text-center py-3">
                <h4 class="mb-0"><i class="bi bi-box-arrow-in-right"></i> Connexion</h4>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold" for="email">Adresse e-mail</label>
                        <input id="email" type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email') }}" required autofocus autocomplete="email">
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold" for="password">Mot de passe</label>
                        <input id="password" type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                               required autocomplete="current-password">
                        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                        <label class="form-check-label" for="remember">Se souvenir de moi</label>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-box-arrow-in-right"></i> Se connecter
                    </button>
                </form>
                <hr class="my-4">
                <p class="text-center text-secondary mb-0">
                    Pas encore de compte ?
                    <a href="{{ route('register.client') }}" class="link-primary">Client</a> ·
                    <a href="{{ route('register.livreur') }}" class="link-primary">Livreur</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
