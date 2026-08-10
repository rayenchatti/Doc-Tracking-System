@extends('layouts.app')

@section('title', 'Mon profil')

@section('content')
    <div class="mb-4">
        <h1 class="h3 page-title mb-1">Mon profil</h1>
        <p class="text-muted mb-0">Gérer vos informations personnelles et votre mot de passe.</p>
    </div>

    @if (session('status') === 'profile-updated')
        <div class="alert alert-success alert-dismissible fade show">
            Vos informations ont été mises à jour.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('status') === 'password-updated')
        <div class="alert alert-success alert-dismissible fade show">
            Votre mot de passe a été modifié.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-3">
        <div class="col-lg-7">
            @include('profile.partials.update-profile-information-form')
        </div>

        <div class="col-lg-5">
            @include('profile.partials.update-password-form')
        </div>
    </div>
@endsection
