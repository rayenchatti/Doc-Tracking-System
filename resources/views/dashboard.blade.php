@extends('layouts.app')

@section('title', 'Tableau de bord')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 page-title mb-1">Tableau de bord</h1>
            <p class="text-muted mb-0">
                Bonjour {{ auth()->user()->prenom }}, voici l'activité du système.
            </p>
        </div>
    </div>

    {{-- Compteurs par statut : issus d'une requete groupBy, aucune valeur codee en dur --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4 col-lg-2">
            <div class="card stat-card h-100">
                <div class="card-body">
                    <div class="text-muted small text-uppercase">Total documents</div>
                    <div class="stat-value">{{ $totalDocuments }}</div>
                </div>
            </div>
        </div>

        @foreach ($parStatut as $ligne)
            <div class="col-md-4 col-lg-2">
                <div class="card stat-card h-100">
                    <div class="card-body">
                        <div class="text-muted small text-uppercase">{{ $ligne['nom'] }}</div>
                        <div class="stat-value">{{ $ligne['total'] }}</div>
                    </div>
                </div>
            </div>
        @endforeach

        <div class="col-md-4 col-lg-2">
            <div class="card stat-card h-100">
                <div class="card-body">
                    <div class="text-muted small text-uppercase">Utilisateurs actifs</div>
                    <div class="stat-value">{{ $totalUtilisateurs }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="bi bi-clock-history me-1"></i> Derniers documents ajoutés</span>
        </div>
        <div class="card-body p-0">
            @if ($derniersDocuments->isEmpty())
                <p class="text-muted text-center my-4 mb-4">
                    Aucun document enregistré pour le moment.
                </p>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Numéro</th>
                                <th>Nom</th>
                                <th>Type</th>
                                <th>Service</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($derniersDocuments as $document)
                                <tr>
                                    <td>{{ $document->numero }}</td>
                                    <td>{{ $document->nom }}</td>
                                    <td>{{ $document->typeDocument?->nom }}</td>
                                    <td>{{ $document->service?->nom }}</td>
                                    <td><span class="badge badge-navy">{{ $document->statut?->nom }}</span></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection
