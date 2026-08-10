@extends('layouts.app')

@section('title', 'Archivage')

@section('content')
    <div class="mb-4">
        <h1 class="h3 page-title mb-1">Documents Archivés</h1>
        <p class="text-muted mb-0">Documents ayant atteint l'étape finale du cycle de traitement.</p>
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
                                </td>
                                <td><span class="badge bg-light text-dark border">{{ $document->typeDocument->nom ?? '—' }}</span></td>
                                <td>{{ $document->service->nom ?? '—' }}</td>
                                <td>{{ \Carbon\Carbon::parse($document->date_document)->format('d/m/Y') }}</td>
                                <td>{{ $document->utilisateur->prenom ?? '' }} {{ $document->utilisateur->nom ?? '' }}</td>
                                <td class="text-end">
                                    <a href="{{ route('documents.show', $document) }}" class="btn btn-sm btn-outline-primary" title="Détails">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('suivi.show', $document) }}" class="btn btn-sm btn-outline-info" title="Historique">
                                        <i class="bi bi-clock-history"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    <i class="bi bi-archive d-block fs-1 mb-2"></i>
                                    Aucun document archivé pour le moment.
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
