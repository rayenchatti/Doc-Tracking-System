<x-app-layout>
    <x-slot name="header">
        Tableau de bord
    </x-slot>

    <div class="row">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4">
                    <h4 class="card-title fw-bold text-primary mb-2">Bienvenue, {{ Auth::user()->prenom }} {{ Auth::user()->nom }} !</h4>
                    <p class="card-text text-muted">Vous êtes connecté en tant que <span class="badge bg-secondary">{{ Auth::user()->role }}</span>.</p>
                    <hr>
                    <div class="row g-3 mt-2">
                        <div class="col-md-4">
                            <div class="card bg-primary text-white border-0 shadow-sm p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-0 text-white-50">Gestion Documentaire</h6>
                                        <h4 class="fw-bold mt-1 mb-0">Documents</h4>
                                    </div>
                                    <i class="bi bi-folder2-open fs-1 opacity-75"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-success text-white border-0 shadow-sm p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-0 text-white-50">Recherche Rapide</h6>
                                        <h4 class="fw-bold mt-1 mb-0">Multicritères</h4>
                                    </div>
                                    <i class="bi bi-search fs-1 opacity-75"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-info text-white border-0 shadow-sm p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-0 text-white-50">Archivage</h6>
                                        <h4 class="fw-bold mt-1 mb-0">Documents Traités</h4>
                                    </div>
                                    <i class="bi bi-archive fs-1 opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
