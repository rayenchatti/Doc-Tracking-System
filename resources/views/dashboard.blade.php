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

    @if ($totalDocuments > 0)
        <div class="row g-3 mb-4">
            <div class="col-lg-5">
                <div class="card h-100">
                    <div class="card-header">
                        <i class="bi bi-pie-chart me-1"></i> Répartition des documents par statut
                    </div>
                    <div class="card-body">
                        <canvas id="chartDashboard" height="220"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="card h-100">
                    <div class="card-header">
                        <i class="bi bi-exclamation-triangle me-1"></i> Écarts / anomalies comptables
                    </div>
                    <div class="card-body d-flex flex-column justify-content-center">
                        @if ($nbAnomalies > 0)
                            <div class="d-flex align-items-center gap-3">
                                <div class="stat-value text-danger mb-0">{{ $nbAnomalies }}</div>
                                <div>
                                    <div class="fw-semibold">
                                        document{{ $nbAnomalies > 1 ? 's' : '' }} signalé{{ $nbAnomalies > 1 ? 's' : '' }}
                                        en anomalie
                                    </div>
                                    @if ($montantAnomalies > 0)
                                        <div class="text-muted small">
                                            Montant cumulé :
                                            <strong>{{ number_format($montantAnomalies, 3, ',', ' ') }} TND</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @else
                            <p class="text-muted mb-0">
                                <i class="bi bi-check-circle text-success me-1"></i>
                                Aucun écart comptable signalé.
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

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
                                    <td>
                                        {{ $document->nom }}
                                        @if ($document->is_anomalie)
                                            <span class="badge bg-danger ms-1">
                                                <i class="bi bi-exclamation-triangle-fill"></i> Anomalie
                                            </span>
                                        @endif
                                    </td>
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

@push('scripts')
    @if ($totalDocuments > 0)
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
        <script>
            new Chart(document.getElementById('chartDashboard'), {
                type: 'doughnut',
                data: {
                    labels: @json($parStatut->pluck('nom')),
                    datasets: [{
                        data: @json($parStatut->pluck('total')),
                        backgroundColor: ['#F5C518', '#4A55C4', '#1B2073', '#8A90AE'],
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { position: 'bottom' } }
                }
            });
        </script>
    @endif
@endpush
