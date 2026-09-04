<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Livre and Go')</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">

    <style>
        body { background-color: var(--bs-light); }
        .navbar-brand { font-weight: 700; }
        #map { min-height: 420px; width: 100%; border-radius: var(--bs-border-radius); }
        .chat-box {
            height: 380px; overflow-y: auto; background-color: var(--bs-white);
            border: var(--bs-border-width) solid var(--bs-border-color);
            border-radius: var(--bs-border-radius); padding: 1rem;
        }
        .msg-me { text-align: right; }
        .msg-bubble {
            display: inline-block; padding: .6rem .8rem; border-radius: 1rem;
            margin: .25rem 0; max-width: 75%; overflow-wrap: anywhere;
        }
        .msg-me .msg-bubble { background-color: var(--bs-primary); color: var(--bs-white); }
        .msg-other .msg-bubble { background-color: var(--bs-secondary-bg); }
        .table > :not(caption) > * > * { vertical-align: middle; }
    </style>
    @stack('styles')
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <i class="bi bi-truck"></i> Livre and Go
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#mainNavbar" aria-controls="mainNavbar"
                    aria-expanded="false" aria-label="Afficher le menu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNavbar">
                <div class="d-flex flex-column flex-lg-row align-items-stretch align-items-lg-center gap-2 ms-lg-auto mt-3 mt-lg-0">
                    @auth
                        @if(auth()->user()->isClient())
                            <a href="{{ route('client.dashboard') }}" class="btn btn-light btn-sm">
                                <i class="bi bi-speedometer2"></i> Tableau de bord
                            </a>
                            <a href="{{ route('client.orders.create') }}" class="btn btn-warning btn-sm">
                                <i class="bi bi-plus-circle"></i> Nouvelle commande
                            </a>
                        @elseif(auth()->user()->isLivreur())
                            <a href="{{ route('livreur.dashboard') }}" class="btn btn-light btn-sm">
                                <i class="bi bi-speedometer2"></i> Tableau de bord
                            </a>
                        @elseif(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-light btn-sm">
                                <i class="bi bi-gear"></i> Administration
                            </a>
                        @endif

                        <form method="POST" action="{{ route('logout') }}" class="d-flex">
                            @csrf
                            <button class="btn btn-danger btn-sm w-100" type="submit">
                                <i class="bi bi-box-arrow-right"></i> Déconnexion
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm">Connexion</a>
                        <a href="{{ route('register.client') }}" class="btn btn-light btn-sm">Inscription client</a>
                        <a href="{{ route('register.livreur') }}" class="btn btn-warning btn-sm">Inscription livreur</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <main class="container my-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                <i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger shadow-sm" role="alert">
                <strong><i class="bi bi-exclamation-triangle-fill"></i> Vérifiez les informations :</strong>
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        const csrfMeta = document.querySelector('meta[name="csrf-token"]');
        window.CSRF_TOKEN = csrfMeta ? csrfMeta.content : '';
    </script>
    @stack('scripts')
</body>
</html>
