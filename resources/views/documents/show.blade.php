@extends('layouts.app')

@section('title', $document->numero)

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 page-title mb-1">{{ $document->nom }}</h1>
            <p class="text-muted mb-0">Référence : {{ $document->numero }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('suivi.show', $document) }}" class="btn btn-poste btn-sm">
                <i class="bi bi-clock-history me-1"></i> Suivi & Historique
            </a>
            <a href="{{ route('documents.edit', $document) }}" class="btn btn-gold btn-sm">
                <i class="bi bi-pencil me-1"></i> Modifier
            </a>
            <a href="{{ route('documents.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Retour
            </a>
        </div>
    </div>

    <div class="row g-4">
        <!-- Informations principales -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-file-earmark-text me-2"></i>Informations du document
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <span class="text-muted d-block small text-uppercase">Numéro de référence</span>
                            <span class="fw-bold">{{ $document->numero }}</span>
                        </div>
                        <div class="col-sm-6">
                            <span class="text-muted d-block small text-uppercase">Type de document</span>
                            <span class="badge bg-light text-dark border">{{ $document->typeDocument->nom ?? '—' }}</span>
                        </div>
                        <div class="col-sm-6">
                            <span class="text-muted d-block small text-uppercase">Service concerné</span>
                            <span class="fw-semibold">{{ $document->service->nom ?? '—' }}</span>
                        </div>
                        <div class="col-sm-6">
                            <span class="text-muted d-block small text-uppercase">Date du document</span>
                            <span class="fw-semibold">{{ \Carbon\Carbon::parse($document->date_document)->format('d/m/Y') }}</span>
                        </div>
                        <div class="col-sm-6">
                            <span class="text-muted d-block small text-uppercase">Responsable</span>
                            <span class="fw-semibold">{{ $document->utilisateur->prenom ?? '' }} {{ $document->utilisateur->nom ?? '' }}</span>
                        </div>
                        <div class="col-sm-6">
                            <span class="text-muted d-block small text-uppercase">Statut courant</span>
                            @php
                                $badgeClass = match($document->statut_id) {
                                    1 => 'bg-warning text-dark',
                                    2 => 'bg-info text-white',
                                    3 => 'bg-success',
                                    4 => 'bg-secondary',
                                    default => 'bg-light text-dark'
                                };
                            @endphp
                            <span class="badge {{ $badgeClass }} fs-6">{{ $document->statut->nom ?? '—' }}</span>
                        </div>
                    </div>

                    <hr class="my-4">

                    @if($document->is_anomalie || $document->montant !== null)
                        <div class="alert {{ $document->is_anomalie ? 'alert-danger' : 'alert-secondary' }} d-flex align-items-center gap-3">
                            @if($document->is_anomalie)
                                <i class="bi bi-exclamation-triangle-fill fs-4"></i>
                            @else
                                <i class="bi bi-cash-coin fs-4"></i>
                            @endif
                            <div>
                                @if($document->is_anomalie)
                                    <div class="fw-bold">Écart / anomalie comptable signalé</div>
                                @endif
                                @if($document->montant !== null)
                                    <div>
                                        Montant de l'opération / écart :
                                        <strong>{{ number_format($document->montant, 3, ',', ' ') }} TND</strong>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <h6 class="fw-bold mb-2">Description</h6>
                    <p class="text-muted bg-light p-3 rounded-2 mb-0">{{ $document->description ?: 'Aucune description.' }}</p>

                    <hr class="my-4">

                    <h6 class="fw-bold mb-2">Fichier joint</h6>
                    @if($document->fichier)
                        <div class="d-flex align-items-center gap-3 bg-light p-3 rounded-3 border">
                            <i class="bi bi-file-earmark fs-2 text-danger"></i>
                            <div>
                                <span class="fw-medium d-block">{{ basename($document->fichier) }}</span>
                                <small class="text-muted">Fichier stocké sur le serveur</small>
                            </div>
                            <a href="{{ asset('storage/' . $document->fichier) }}" target="_blank" class="btn btn-outline-primary btn-sm ms-auto">
                                <i class="bi bi-download me-1"></i> Ouvrir
                            </a>
                        </div>
                    @else
                        <p class="text-muted fst-italic mb-0">Aucun fichier joint.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar : dernière activité -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-clock-history me-2"></i>Dernière activité
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse($document->historiques->sortByDesc('date_action')->take(5) as $h)
                            <li class="list-group-item px-3 py-2">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="fw-semibold small">{{ $h->utilisateur->prenom ?? '' }} {{ $h->utilisateur->nom ?? '' }}</span>
                                    <small class="text-muted">{{ \Carbon\Carbon::parse($h->date_action)->format('d/m/Y H:i') }}</small>
                                </div>
                                <small class="text-muted">
                                    @if($h->ancienStatut)
                                        {{ $h->ancienStatut->nom }} →
                                    @else
                                        Création →
                                    @endif
                                    <span class="badge badge-navy">{{ $h->nouveauStatut->nom ?? '—' }}</span>
                                </small>
                            </li>
                        @empty
                            <li class="list-group-item text-muted text-center py-3">Aucun historique.</li>
                        @endforelse
                    </ul>
                </div>
                <div class="card-footer bg-white text-center py-2">
                    <a href="{{ route('suivi.show', $document) }}" class="small text-decoration-none">
                        Voir tout l'historique →
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
