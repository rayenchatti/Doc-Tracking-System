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

    {{-- Graphique interactif : affiche uniquement lorsqu'il y a des donnees reelles --}}
    @if ($totalDocuments > 0)
        <div class="card mt-3">
            <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
                <span><i class="bi bi-graph-up me-1"></i> Graphique interactif</span>

                <div class="d-flex flex-wrap align-items-center gap-2">
                    {{-- Filtre : quelle donnee afficher --}}
                    <div class="input-group input-group-sm" style="width: auto;">
                        <label class="input-group-text" for="filtreDonnees">Données</label>
                        <select id="filtreDonnees" class="form-select form-select-sm">
                            <option value="statut">Par statut</option>
                            <option value="type">Par type de document</option>
                            <option value="service">Par service</option>
                            <option value="utilisateur">Par responsable</option>
                        </select>
                    </div>

                    {{-- Filtre : type de graphique --}}
                    <div class="input-group input-group-sm" style="width: auto;">
                        <label class="input-group-text" for="filtreType">Type</label>
                        <select id="filtreType" class="form-select form-select-sm">
                            <option value="bar">Barres verticales</option>
                            <option value="horizontalBar">Barres horizontales</option>
                            <option value="line">Courbe</option>
                            <option value="doughnut">Anneau</option>
                            <option value="pie">Camembert</option>
                            <option value="polarArea">Aire polaire</option>
                            <option value="radar">Radar</option>
                        </select>
                    </div>

                    <button type="button" id="btnResetZoom" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-arrows-angle-contract me-1"></i> Réinitialiser le zoom
                    </button>
                </div>
            </div>

            <div class="card-body">
                <p class="text-muted small mb-3">
                    <i class="bi bi-info-circle me-1"></i>
                    Molette de la souris pour zoomer, cliquer-glisser pour se déplacer sur les axes X et Y.
                    Le zoom est disponible sur les graphiques en barres et en courbe.
                </p>
                <div style="height: 420px;">
                    <canvas id="chartInteractif"></canvas>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('scripts')
    @if ($totalDocuments > 0)
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
        {{-- Plugin de zoom / deplacement sur les axes (molette + cliquer-glisser) --}}
        <script src="https://cdn.jsdelivr.net/npm/hammerjs@2.0.8/hammer.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-zoom@2.0.1/dist/chartjs-plugin-zoom.min.js"></script>
        <script>
            const navy = '#1B2073';
            const gold = '#F5C518';
            const palette = [gold, navy, '#4A55C4', '#8A90AE', '#E8A33D',
                             '#5C67D1', '#2F3699', '#B9BDD4'];

            // Jeux de donnees fournis par StatistiqueController (requetes groupBy).
            const donnees = {
                statut: {
                    titre: 'Documents par statut',
                    labels: @json($parStatut->pluck('libelle')),
                    valeurs: @json($parStatut->pluck('total')),
                },
                type: {
                    titre: 'Documents par type',
                    labels: @json($parType->pluck('libelle')),
                    valeurs: @json($parType->pluck('total')),
                },
                service: {
                    titre: 'Documents par service',
                    labels: @json($parService->pluck('libelle')),
                    valeurs: @json($parService->pluck('total')),
                },
                utilisateur: {
                    titre: 'Documents par responsable',
                    labels: @json($parUtilisateur->pluck('libelle')),
                    valeurs: @json($parUtilisateur->pluck('total')),
                },
            };

            // Le zoom n'a de sens que sur les graphiques a axes.
            const TYPES_AVEC_AXES = ['bar', 'horizontalBar', 'line'];

            const selectDonnees = document.getElementById('filtreDonnees');
            const selectType = document.getElementById('filtreType');
            const btnReset = document.getElementById('btnResetZoom');
            let graphique = null;

            function construireConfig() {
                const jeu = donnees[selectDonnees.value];
                const typeChoisi = selectType.value;
                const aDesAxes = TYPES_AVEC_AXES.includes(typeChoisi);

                // 'horizontalBar' n'existe plus dans Chart.js v4 : c'est un 'bar'
                // avec indexAxis = 'y'.
                const typeChartJs = typeChoisi === 'horizontalBar' ? 'bar' : typeChoisi;
                const couleurs = aDesAxes ? navy : palette;

                const config = {
                    type: typeChartJs,
                    data: {
                        labels: jeu.labels,
                        datasets: [{
                            label: 'Documents',
                            data: jeu.valeurs,
                            backgroundColor: couleurs,
                            borderColor: typeChoisi === 'line' ? navy : '#ffffff',
                            borderWidth: typeChoisi === 'line' ? 2 : 1,
                            fill: false,
                            tension: 0.3,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            title: { display: true, text: jeu.titre, font: { size: 15 } },
                            legend: {
                                display: !aDesAxes,
                                position: 'bottom',
                            },
                            zoom: {
                                zoom: {
                                    wheel: { enabled: aDesAxes },
                                    pinch: { enabled: aDesAxes },
                                    mode: 'xy',
                                },
                                pan: { enabled: aDesAxes, mode: 'xy' },
                            },
                        },
                    }
                };

                if (typeChoisi === 'horizontalBar') {
                    config.options.indexAxis = 'y';
                }

                if (aDesAxes) {
                    config.options.scales = {
                        x: { beginAtZero: true, ticks: { precision: 0 } },
                        y: { beginAtZero: true, ticks: { precision: 0 } },
                    };
                }

                return config;
            }

            function dessiner() {
                if (graphique) {
                    graphique.destroy();
                }
                graphique = new Chart(document.getElementById('chartInteractif'), construireConfig());

                // Le bouton de reinitialisation ne sert que si le zoom est actif.
                btnReset.disabled = !TYPES_AVEC_AXES.includes(selectType.value);
            }

            selectDonnees.addEventListener('change', dessiner);
            selectType.addEventListener('change', dessiner);
            btnReset.addEventListener('click', () => {
                if (graphique && typeof graphique.resetZoom === 'function') {
                    graphique.resetZoom();
                }
            });

            dessiner();
        </script>
    @endif
@endpush
