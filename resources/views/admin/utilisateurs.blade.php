@extends('layouts.app')

@section('title', 'Utilisateurs')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h3 class="mb-1"><i class="bi bi-people text-primary"></i> Utilisateurs</h3>
        <p class="text-secondary mb-0">Gérez les comptes de la plateforme.</p>
    </div>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Retour</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr><th>#</th><th>Nom</th><th>Email</th><th>Rôle</th><th>En ligne</th><th>Action</th></tr>
                </thead>
                <tbody>
                    @forelse($utilisateurs as $u)
                        <tr>
                            <td>{{ $u->id }}</td>
                            <td class="fw-semibold">{{ $u->name }}</td>
                            <td>{{ $u->email }}</td>
                            <td>
                                @php $roleClass = ['admin'=>'text-bg-danger','livreur'=>'text-bg-warning','client'=>'text-bg-primary']; @endphp
                                <span class="badge {{ $roleClass[$u->role] ?? 'text-bg-secondary' }}">{{ ucfirst($u->role) }}</span>
                            </td>
                            <td>
                                @if($u->is_online)
                                    <span class="badge text-bg-success"><i class="bi bi-circle-fill"></i> En ligne</span>
                                @else
                                    <span class="badge text-bg-secondary">Hors ligne</span>
                                @endif
                            </td>
                            <td>
                                @if(!$u->isAdmin())
                                    <form method="POST" action="{{ route('admin.utilisateurs.supprimer', $u) }}"
                                          onsubmit="return confirm('Supprimer cet utilisateur ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" type="submit">
                                            <i class="bi bi-trash"></i> Supprimer
                                        </button>
                                    </form>
                                @else
                                    <span class="text-secondary">Protégé</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-secondary py-4">Aucun utilisateur.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($utilisateurs->hasPages())
        <div class="card-footer bg-white">{{ $utilisateurs->links() }}</div>
    @endif
</div>
@endsection
