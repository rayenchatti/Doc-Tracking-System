@extends('layouts.app')

@section('title', 'Nouveau document')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 page-title mb-1">Nouveau Document</h1>
            <p class="text-muted mb-0">Remplir les informations du document à enregistrer.</p>
        </div>
        <a href="{{ route('documents.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Retour à la liste
        </a>
    </div>

    <div class="card">
        <div class="card-body p-4">
            <form method="POST" action="{{ route('documents.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="numero" class="form-label small fw-semibold">Numéro de référence <span class="text-danger">*</span></label>
                        <input type="text" name="numero" id="numero" class="form-control @error('numero') is-invalid @enderror"
                               value="{{ old('numero') }}" placeholder="Ex : DOC-2026-001" required>
                        @error('numero') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="nom" class="form-label small fw-semibold">Nom du document <span class="text-danger">*</span></label>
                        <input type="text" name="nom" id="nom" class="form-control @error('nom') is-invalid @enderror"
                               value="{{ old('nom') }}" placeholder="Ex : Facture Fournisseur X" required>
                        @error('nom') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label for="type_document_id" class="form-label small fw-semibold">Type de document <span class="text-danger">*</span></label>
                        <select name="type_document_id" id="type_document_id" class="form-select @error('type_document_id') is-invalid @enderror" required>
                            <option value="">— Sélectionner un type —</option>
                            @foreach($types as $type)
                                <option value="{{ $type->id }}" @selected(old('type_document_id') == $type->id)>{{ $type->nom }}</option>
                            @endforeach
                        </select>
                        @error('type_document_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="service_id" class="form-label small fw-semibold">Service concerné <span class="text-danger">*</span></label>
                        <select name="service_id" id="service_id" class="form-select @error('service_id') is-invalid @enderror" required>
                            <option value="">— Sélectionner un service —</option>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}" @selected(old('service_id') == $service->id)>{{ $service->nom }}</option>
                            @endforeach
                        </select>
                        @error('service_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="date_document" class="form-label small fw-semibold">Date du document <span class="text-danger">*</span></label>
                        <input type="date" name="date_document" id="date_document" class="form-control @error('date_document') is-invalid @enderror"
                               value="{{ old('date_document', date('Y-m-d')) }}" required>
                        @error('date_document') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label small fw-semibold">Description / Observations</label>
                    <textarea name="description" id="description" rows="3" class="form-control @error('description') is-invalid @enderror"
                              placeholder="Détails supplémentaires…">{{ old('description') }}</textarea>
                    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Suivi des ecarts / anomalies comptables --}}
                <div class="card bg-light border-0 mb-3">
                    <div class="card-body py-3">
                        <div class="form-check mb-3">
                            <input type="hidden" name="is_anomalie" value="0">
                            <input class="form-check-input" type="checkbox" name="is_anomalie" value="1"
                                   id="is_anomalie" @checked(old('is_anomalie'))>
                            <label class="form-check-label fw-semibold" for="is_anomalie">
                                <i class="bi bi-exclamation-triangle text-danger me-1"></i>
                                Signaler un écart / anomalie comptable
                            </label>
                        </div>

                        <div class="row">
                            <div class="col-md-5">
                                <label for="montant" class="form-label small fw-semibold">
                                    Montant de l'opération / écart (TND)
                                </label>
                                <div class="input-group">
                                    <input type="number" step="0.001" min="0" name="montant" id="montant"
                                           class="form-control @error('montant') is-invalid @enderror"
                                           value="{{ old('montant') }}" placeholder="0.000">
                                    <span class="input-group-text">TND</span>
                                    @error('montant') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="fichier" class="form-label small fw-semibold">Fichier numérique (PDF, Word, Image — max 10 Mo)</label>
                    <input type="file" name="fichier" id="fichier" class="form-control @error('fichier') is-invalid @enderror">
                    @error('fichier') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('documents.index') }}" class="btn btn-outline-secondary">Annuler</a>
                    <button type="submit" class="btn btn-gold">
                        <i class="bi bi-save me-1"></i> Créer le document
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
