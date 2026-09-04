@extends('layouts.app')

@section('title', 'Accueil')

@section('content')
<div class="p-4 p-md-5 mb-4 bg-white rounded-4 shadow-sm border">
    <div class="text-center py-md-3">
        <span class="badge text-bg-primary mb-3"><i class="bi bi-truck"></i> Livraison simple et rapide</span>
        <h1 class="display-5 fw-bold">Bienvenue sur Livre and Go</h1>
        <p class="lead text-secondary col-lg-8 mx-auto">
            Commandez une livraison en quelques clics, choisissez votre livreur,
            suivez votre colis sur la carte et échangez avec lui directement.
        </p>

        @guest
            <div class="d-flex flex-column flex-sm-row justify-content-center gap-2 mt-4">
                <a href="{{ route('register.client') }}" class="btn btn-primary btn-lg">
                    <i class="bi bi-person"></i> Je suis client
                </a>
                <a href="{{ route('register.livreur') }}" class="btn btn-warning btn-lg">
                    <i class="bi bi-bicycle"></i> Je suis livreur
                </a>
                <a href="{{ route('login') }}" class="btn btn-outline-secondary btn-lg">
                    Se connecter
                </a>
            </div>
        @endguest
    </div>
</div>

<div class="row g-4">
    <div class="col-md-4">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="fs-2 text-primary mb-3"><i class="bi bi-box-seam"></i></div>
                <h5 class="card-title">Client</h5>
                <p class="card-text text-secondary mb-0">
                    Créez une commande, indiquez les adresses de retrait et de livraison,
                    puis suivez votre livreur.
                </p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="fs-2 text-warning mb-3"><i class="bi bi-bicycle"></i></div>
                <h5 class="card-title">Livreur</h5>
                <p class="card-text text-secondary mb-0">
                    Inscrivez-vous, passez en ligne, acceptez les commandes et
                    partagez votre position en temps réel.
                </p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="fs-2 text-success mb-3"><i class="bi bi-shield-check"></i></div>
                <h5 class="card-title">Administration</h5>
                <p class="card-text text-secondary mb-0">
                    Supervisez les utilisateurs, les commandes et l'activité
                    globale de la plateforme.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
