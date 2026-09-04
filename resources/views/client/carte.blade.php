@extends('layouts.app')

@section('title', 'Carte des livreurs')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
    <div>
        <h4 class="mb-1"><i class="bi bi-map text-primary"></i> Carte des livreurs en ligne</h4>
        <p class="text-secondary mb-0">La position des livreurs disponibles est actualisée automatiquement.</p>
    </div>
    <a href="{{ route('client.dashboard') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Retour
    </a>
</div>
<div class="card border-0 shadow-sm">
    <div class="card-body p-2">
        <div id="map"></div>
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

    function escapeHtml(value) {
        const div = document.createElement('div');
        div.textContent = value ?? '';
        return div.innerHTML;
    }

    function rafraichir() {
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

    rafraichir();
    setInterval(rafraichir, 5000);
</script>
@endpush
