@extends('layouts.app')

@section('title', 'Modifier — ' . $document->numero)

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 page-title mb-1">Modifier le document</h1>
            <p class="text-muted mb-0">Référence : {{ $document->numero }}</p>
        </div>
        <a href="{{ route('documents.show', $document) }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Annuler
        </a>
    </div>

    <div class="card">
        <div class="card-body p-4">
            <form method="POST" action="{{ route('documents.update', $document) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="numero" class="form-label small fw-semibold">Numéro de référence <span class="text-danger">*</span></label>
                        <input type="text" name="numero" id="numero" class="form-control @error('numero') is-invalid @enderror"
                               value="{{ old('numero', $document->numero) }}" required>
                        @error('numero') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="nom" class="form-label small fw-semibold">Nom du document <span class="text-danger">*</span></label>
                        <input type="text" name="nom" id="nom" class="form-control @error('nom') is-invalid @enderror"
                               value="{{ old('nom', $document->nom) }}" required>
                        @error('nom') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label for="type_document_id" class="form-label small fw-semibold">Type de document <span class="text-danger">*</span></label>
                        <select name="type_document_id" id="type_document_id" class="form-select @error('type_document_id') is-invalid @enderror" required>
                            @foreach($types as $type)
                                <option value="{{ $type->id }}" @selected(old('type_document_id', $document->type_document_id) == $type->id)>{{ $type->nom }}</option>
                            @endforeach
                        </select>
                        @error('type_document_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="service_id" class="form-label small fw-semibold">Service concerné <span class="text-danger">*</span></label>
                        <select name="service_id" id="service_id" class="form-select @error('service_id') is-invalid @enderror" required>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}" @selected(old('service_id', $document->service_id) == $service->id)>{{ $service->nom }}</option>
                            @endforeach
                        </select>
                        @error('service_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="date_document" class="form-label small fw-semibold">Date du document <span class="text-danger">*</span></label>
                        <input type="date" name="date_document" id="date_document" class="form-control @error('date_document') is-invalid @enderror"
                               value="{{ old('date_document', $document->date_document) }}" required>
                        @error('date_document') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label small fw-semibold">Description / Observations</label>
                    <textarea name="description" id="description" rows="3" class="form-control @error('description') is-invalid @enderror">{{ old('description', $document->description) }}</textarea>
                    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-4">
                    <label for="fichier" class="form-label small fw-semibold">Remplacer le fichier (optionnel)</label>
                    @if($document->fichier)
                        <div class="mb-2 text-muted small">
                            <i class="bi bi-paperclip"></i> Fichier actuel : {{ basename($document->fichier) }}
                        </div>
                    @endif
                    <input type="file" name="fichier" id="fichier" class="form-control @error('fichier') is-invalid @enderror">
                    @error('fichier') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('documents.show', $document) }}" class="btn btn-outline-secondary">Annuler</a>
                    <button type="submit" class="btn btn-gold">
                        <i class="bi bi-save me-1"></i> Enregistrer les modifications
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
