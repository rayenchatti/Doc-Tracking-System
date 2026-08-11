@extends('layouts.app')

@section('title', 'Documents')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 page-title mb-1">Liste des Documents Actifs</h1>
            <p class="text-muted mb-0">Documents en cours de traitement (hors archivés).</p>
        </div>
        <a href="{{ route('documents.create') }}" class="btn btn-gold">
            <i class="bi bi-plus-lg me-1"></i> Nouveau document
        </a>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('documents.index') }}" class="row g-2 align-items-end">
                <div class="col-md-8">
                    <label class="form-label small fw-semibold">Rechercher</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                           class="form-control" placeholder="Numéro ou nom du document…">
                </div>
                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-poste w-100">Rechercher</button>
                    @if(request('search'))
                        <a href="{{ route('documents.index') }}" class="btn btn-outline-secondary" title="Réinitialiser">↺</a>
                    @endif
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
                            <th>Numéro</th>
                            <th>Nom</th>
                            <th>Type</th>
                            <th>Service</th>
                            <th>Date</th>
                            <th>Statut</th>
                            <th>Responsable</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($documents as $document)
                            <tr>
                                <td class="fw-semibold">{{ $document->numero }}</td>
                                <td>
                                    <a href="{{ route('documents.show', $document) }}" class="text-decoration-none">
                                        {{ $document->nom }}
                                    </a>
                                    @if($document->is_anomalie)
                                        <span class="badge bg-danger ms-1" title="Écart / anomalie comptable signalé">
                                            <i class="bi bi-exclamation-triangle-fill"></i> Anomalie
                                        </span>
                                    @endif
                                </td>
                                <td><span class="badge bg-light text-dark border">{{ $document->typeDocument->nom ?? '—' }}</span></td>
                                <td>{{ $document->service->nom ?? '—' }}</td>
                                <td>{{ \Carbon\Carbon::parse($document->date_document)->format('d/m/Y') }}</td>
                                <td>
                                    @php
                                        $badgeClass = match($document->statut_id) {
                                            1 => 'bg-warning text-dark',
                                            2 => 'bg-info text-white',
                                            3 => 'bg-success',
                                            4 => 'bg-secondary',
                                            default => 'bg-light text-dark'
                                        };
                                    @endphp
                                    <span class="badge {{ $badgeClass }}">{{ $document->statut->nom ?? '—' }}</span>
                                </td>
                                <td>{{ $document->utilisateur->prenom ?? '' }} {{ $document->utilisateur->nom ?? '' }}</td>
                                <td class="text-end">
                                    <a href="{{ route('documents.show', $document) }}" class="btn btn-sm btn-outline-primary" title="Détails">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('suivi.show', $document) }}" class="btn btn-sm btn-outline-info" title="Suivi">
                                        <i class="bi bi-clock-history"></i>
                                    </a>
                                    <a href="{{ route('documents.edit', $document) }}" class="btn btn-sm btn-outline-secondary" title="Modifier">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    <i class="bi bi-folder-x d-block fs-1 mb-2"></i>
                                    Aucun document actif trouvé.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($documents->hasPages())
            <div class="card-footer bg-white">
                {{ $documents->links() }}
            </div>
        @endif
    </div>
@endsection
