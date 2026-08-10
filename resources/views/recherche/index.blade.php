@extends('layouts.app')

@section('title', 'Recherche')

@section('content')
    <div class="mb-4">
        <h1 class="h3 page-title mb-1">Recherche Multicritère</h1>
        <p class="text-muted mb-0">Combinez les filtres pour trouver un document précis.</p>
    </div>

    <!-- Formulaire de recherche -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="bi bi-funnel me-2"></i>Critères de recherche
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('recherche.index') }}">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">Numéro</label>
                        <input type="text" name="numero" value="{{ request('numero') }}" class="form-control"
                               placeholder="Ex : DOC-2026-…">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">Nom du document</label>
                        <input type="text" name="nom" value="{{ request('nom') }}" class="form-control"
                               placeholder="Contient…">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">Type de document</label>
                        <select name="type_document_id" class="form-select">
                            <option value="">Tous les types</option>
                            @foreach($types as $type)
                                <option value="{{ $type->id }}" @selected(request('type_document_id') == $type->id)>{{ $type->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">Service</label>
                        <select name="service_id" class="form-select">
                            <option value="">Tous les services</option>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}" @selected(request('service_id') == $service->id)>{{ $service->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">Statut</label>
                        <select name="statut_id" class="form-select">
                            <option value="">Tous les statuts</option>
                            @foreach($statuts as $statut)
                                <option value="{{ $statut->id }}" @selected(request('statut_id') == $statut->id)>{{ $statut->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">Responsable</label>
                        <select name="utilisateur_id" class="form-select">
                            <option value="">Tous</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" @selected(request('utilisateur_id') == $user->id)>{{ $user->prenom }} {{ $user->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">Date début</label>
                        <input type="date" name="date_debut" value="{{ request('date_debut') }}" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">Date fin</label>
                        <input type="date" name="date_fin" value="{{ request('date_fin') }}" class="form-control">
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-3">
                    <a href="{{ route('recherche.index') }}" class="btn btn-outline-secondary">Réinitialiser</a>
                    <button type="submit" class="btn btn-poste">
                        <i class="bi bi-search me-1"></i> Rechercher
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Résultats -->
    @if($hasSearched)
        <div class="card">
            <div class="card-header">
                <i class="bi bi-list-ul me-2"></i>Résultats ({{ $documents->total() }} document{{ $documents->total() > 1 ? 's' : '' }})
            </div>
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
                                    <td>{{ $document->nom }}</td>
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
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">
                                        Aucun document ne correspond aux critères de recherche.
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
    @endif
@endsection
