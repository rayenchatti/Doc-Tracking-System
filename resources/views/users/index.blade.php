@extends('layouts.app')

@section('title', 'Utilisateurs')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 page-title mb-1">Gestion des utilisateurs</h1>
            <p class="text-muted mb-0">Comptes ayant accès à l'application.</p>
        </div>
        <a href="{{ route('users.create') }}" class="btn btn-gold">
            <i class="bi bi-person-plus me-1"></i> Nouvel utilisateur
        </a>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('users.index') }}" class="row g-2 align-items-end">
                <div class="col-md-5">
                    <label class="form-label small fw-semibold">Rechercher</label>
                    <input type="text" name="q" value="{{ request('q') }}"
                           class="form-control" placeholder="Nom, prénom ou e-mail">
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-semibold">Rôle</label>
                    <select name="role" class="form-select">
                        <option value="">Tous</option>
                        <option value="admin" @selected(request('role') === 'admin')>Admin</option>
                        <option value="agent" @selected(request('role') === 'agent')>Agent</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-semibold">Statut</label>
                    <select name="statut" class="form-select">
                        <option value="">Tous</option>
                        <option value="actif" @selected(request('statut') === 'actif')>Actif</option>
                        <option value="desactive" @selected(request('statut') === 'desactive')>Désactivé</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-poste w-100">Filtrer</button>
                    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">↺</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>E-mail</th>
                            <th>Téléphone</th>
                            <th>Rôle</th>
                            <th>Statut</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr>
                                <td class="fw-semibold">{{ $user->nom }}</td>
                                <td>{{ $user->prenom }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->telephone ?: '—' }}</td>
                                <td>
                                    <span class="badge {{ $user->role === 'admin' ? 'badge-gold' : 'badge-navy' }}">
                                        {{ $user->role }}
                                    </span>
                                </td>
                                <td>
                                    @if ($user->statut === 'actif')
                                        <span class="badge bg-success">Actif</span>
                                    @else
                                        <span class="badge bg-secondary">Désactivé</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('users.edit', $user) }}"
                                       class="btn btn-sm btn-outline-primary" title="Modifier">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    @if ($user->id !== auth()->id())
                                        @if ($user->statut === 'actif')
                                            <form method="POST" action="{{ route('users.destroy', $user) }}"
                                                  class="d-inline"
                                                  onsubmit="return confirm('Désactiver ce compte ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-outline-danger" title="Désactiver">
                                                    <i class="bi bi-person-slash"></i>
                                                </button>
                                            </form>
                                        @else
                                            <form method="POST" action="{{ route('users.activate', $user) }}"
                                                  class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button class="btn btn-sm btn-outline-success" title="Réactiver">
                                                    <i class="bi bi-person-check"></i>
                                                </button>
                                            </form>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    Aucun utilisateur trouvé.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if ($users->hasPages())
            <div class="card-footer bg-white">
                {{ $users->links() }}
            </div>
        @endif
    </div>
@endsection
