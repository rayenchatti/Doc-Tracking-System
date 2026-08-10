@extends('layouts.app')

@section('title', 'Nouvel utilisateur')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 page-title mb-0">Nouvel utilisateur</h1>
        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Retour
        </a>
    </div>

    <div class="card">
        <div class="card-header">Informations du compte</div>
        <div class="card-body">
            <form method="POST" action="{{ route('users.store') }}">
                @csrf
                @include('users._form')

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-gold">
                        <i class="bi bi-check-lg me-1"></i> Enregistrer
                    </button>
                    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
@endsection
