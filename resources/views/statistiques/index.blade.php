@extends('layouts.app')

@section('title', 'Statistiques')

@section('content')
    <div class="mb-4">
        <h1 class="h3 page-title mb-1">Statistiques</h1>
        <p class="text-muted mb-0">
            Indicateurs globaux calculés directement depuis la base de données.
        </p>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="text-muted small text-uppercase">Total documents</div>
                    <div class="stat-value">{{ $totalDocuments }}</div>
                </div>
            </div>
        </div>
    </div>

    @if ($totalDocuments === 0)
        <div class="alert alert-info">
            <i class="bi bi-info-circle me-1"></i>
            Aucun document n'a encore été enregistré. Les statistiques s'afficheront
            dès que le module Documents sera alimenté.
        </div>
    @endif

    <div class="row g-3">
        {{-- Repartition par statut --}}
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header"><i class="bi bi-flag me-1"></i> Répartition par statut</div>
                <div class="card-body p-0">
                    <table class="table table-sm table-hover mb-0">
                        <thead>
                            <tr><th>Statut</th><th class="text-end">Nombre</th><th class="text-end">%</th></tr>
                        </thead>
                        <tbody>
                            @forelse ($parStatut as $ligne)
                                <tr>
                                    <td>{{ $ligne->libelle }}</td>
                                    <td class="text-end fw-semibold">{{ $ligne->total }}</td>
                                    <td class="text-end text-muted">
                                        {{ $totalDocuments ? round($ligne->total * 100 / $totalDocuments, 1) : 0 }}%
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="text-center text-muted py-3">Aucune donnée</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Repartition par categorie (type_document) --}}
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header"><i class="bi bi-tags me-1"></i> Répartition par catégorie</div>
                <div class="card-body p-0">
                    <table class="table table-sm table-hover mb-0">
                        <thead>
                            <tr><th>Type de document</th><th class="text-end">Nombre</th></tr>
                        </thead>
                        <tbody>
                            @forelse ($parType as $ligne)
                                <tr>
                                    <td>{{ $ligne->libelle }}</td>
                                    <td class="text-end fw-semibold">{{ $ligne->total }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="2" class="text-center text-muted py-3">Aucune donnée</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Repartition par service --}}
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header"><i class="bi bi-building me-1"></i> Répartition par service</div>
                <div class="card-body p-0">
                    <table class="table table-sm table-hover mb-0">
                        <thead>
                            <tr><th>Service</th><th class="text-end">Nombre</th></tr>
                        </thead>
                        <tbody>
                            @forelse ($parService as $ligne)
                                <tr>
                                    <td>{{ $ligne->libelle }}</td>
                                    <td class="text-end fw-semibold">{{ $ligne->total }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="2" class="text-center text-muted py-3">Aucune donnée</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Repartition par utilisateur responsable --}}
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header"><i class="bi bi-person-badge me-1"></i> Documents par responsable</div>
                <div class="card-body p-0">
                    <table class="table table-sm table-hover mb-0">
                        <thead>
                            <tr><th>Utilisateur</th><th class="text-end">Nombre</th></tr>
                        </thead>
                        <tbody>
                            @forelse ($parUtilisateur as $ligne)
                                <tr>
                                    <td>{{ $ligne->libelle }}</td>
                                    <td class="text-end fw-semibold">{{ $ligne->total }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="2" class="text-center text-muted py-3">Aucune donnée</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Graphiques : affiches uniquement lorsqu'il y a des donnees reelles --}}
    @if ($totalDocuments > 0)
        <div class="row g-3 mt-1">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">Graphique — par statut</div>
                    <div class="card-body"><canvas id="chartStatut" height="200"></canvas></div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">Graphique — par service</div>
                    <div class="card-body"><canvas id="chartService" height="200"></canvas></div>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('scripts')
    @if ($totalDocuments > 0)
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
        <script>
            const navy = '#1B2073';
            const gold = '#F5C518';

            new Chart(document.getElementById('chartStatut'), {
                type: 'doughnut',
                data: {
                    labels: @json($parStatut->pluck('libelle')),
                    datasets: [{
                        data: @json($parStatut->pluck('total')),
                        backgroundColor: [gold, '#4A55C4', navy, '#8A90AE'],
                    }]
                },
                options: { plugins: { legend: { position: 'bottom' } } }
            });

            new Chart(document.getElementById('chartService'), {
                type: 'bar',
                data: {
                    labels: @json($parService->pluck('libelle')),
                    datasets: [{
                        label: 'Documents',
                        data: @json($parService->pluck('total')),
                        backgroundColor: navy,
                    }]
                },
                options: {
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
                }
            });
        </script>
    @endif
@endpush
