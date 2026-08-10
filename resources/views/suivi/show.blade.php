@extends('layouts.app')

@section('title', 'Suivi — ' . $document->numero)

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 page-title mb-1">Suivi du Document</h1>
            <p class="text-muted mb-0">{{ $document->nom }} — Réf. {{ $document->numero }}</p>
        </div>
        <a href="{{ route('documents.show', $document) }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Retour au document
        </a>
    </div>

    <div class="row g-4">
        <!-- Progression visuelle -->
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-signpost-2 me-2"></i>Progression du statut
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center position-relative px-4 py-3">
                        <!-- Barre de fond -->
                        <div class="position-absolute top-50 start-0 end-0 mx-4" style="height:4px; background:#e3e6ef; z-index:0;"></div>
                        <!-- Barre de progression -->
                        @php
                            $progressPercent = match($document->statut_id) {
                                1 => '0%',
                                2 => '33%',
                                3 => '66%',
                                4 => '100%',
                                default => '0%'
                            };
                        @endphp
                        <div class="position-absolute top-50 start-0 mx-4" style="height:4px; background:var(--poste-navy); z-index:1; width:{{ $progressPercent }};"></div>

                        @foreach(\App\Models\Statut::ORDRE as $index => $label)
                            @php
                                $statutId = $index + 1;
                                $isActive = $document->statut_id >= $statutId;
                                $isCurrent = $document->statut_id === $statutId;
                            @endphp
                            <div class="text-center position-relative" style="z-index:2;">
                                <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2"
                                     style="width:40px; height:40px;
                                            background:{{ $isActive ? 'var(--poste-navy)' : '#e3e6ef' }};
                                            color:{{ $isActive ? '#fff' : '#8A90AE' }};
                                            border:{{ $isCurrent ? '3px solid var(--poste-gold)' : 'none' }};">
                                    @if($isActive && !$isCurrent)
                                        <i class="bi bi-check-lg"></i>
                                    @else
                                        {{ $statutId }}
                                    @endif
                                </div>
                                <small class="d-block fw-semibold {{ $isCurrent ? '' : 'text-muted' }}">{{ $label }}</small>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Action : avancer le statut -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-arrow-right-circle me-2"></i>Changer le statut
                </div>
                <div class="card-body">
                    @if($nextStatut)
                        <p class="text-muted small mb-3">
                            Faire passer ce document de
                            <span class="badge badge-navy">{{ $document->statut->nom }}</span>
                            à
                            <span class="badge badge-gold">{{ $nextStatut->nom }}</span>.
                        </p>
                        <form method="POST" action="{{ route('suivi.update', $document) }}"
                              onsubmit="return confirm('Confirmer le passage à \'{{ $nextStatut->nom }}\' ?');">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-gold w-100">
                                <i class="bi bi-arrow-right me-1"></i> Passer à « {{ $nextStatut->nom }} »
                            </button>
                        </form>
                    @else
                        <div class="text-center text-muted py-3">
                            <i class="bi bi-check-circle-fill fs-1 text-success d-block mb-2"></i>
                            <p class="mb-0 fw-semibold">Ce document a atteint l'étape finale.</p>
                            <small>Statut : Archivé</small>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Historique complet -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-clock-history me-2"></i>Historique complet des changements
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Date & Heure</th>
                                    <th>Utilisateur</th>
                                    <th>Ancien statut</th>
                                    <th>Nouveau statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($document->historiques->sortByDesc('date_action') as $h)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($h->date_action)->format('d/m/Y H:i') }}</td>
                                        <td>{{ $h->utilisateur->prenom ?? '' }} {{ $h->utilisateur->nom ?? '' }}</td>
                                        <td>
                                            @if($h->ancienStatut)
                                                <span class="badge bg-light text-dark border">{{ $h->ancienStatut->nom }}</span>
                                            @else
                                                <span class="text-muted fst-italic">— Création —</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge badge-navy">{{ $h->nouveauStatut->nom ?? '—' }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">
                                            Aucun historique enregistré.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
