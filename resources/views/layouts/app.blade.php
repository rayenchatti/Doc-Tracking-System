<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Gestion des documents') - {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --poste-navy: #1B2073;
            --poste-navy-dark: #141855;
            --poste-navy-light: #2B3390;
            --poste-gold: #F5C518;
            --poste-gold-dark: #D9A900;
            --poste-bg: #F4F5FA;
        }

        body {
            background-color: var(--poste-bg);
            color: #21243D;
        }

        /* ---- Navbar ---- */
        .navbar-poste {
            background-color: var(--poste-navy);
            border-bottom: 3px solid var(--poste-gold);
        }
        .navbar-poste .navbar-brand,
        .navbar-poste .nav-link {
            color: #fff;
        }
        .navbar-poste .nav-link:hover { color: var(--poste-gold); }

        /* ---- Logo losange (inspire de l'identite visuelle) ---- */
        .brand-mark {
            width: 34px;
            height: 34px;
            background-color: var(--poste-gold);
            transform: rotate(45deg);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 4px;
            margin-right: .6rem;
        }
        .brand-mark i {
            transform: rotate(-45deg);
            color: var(--poste-navy);
            font-size: 1rem;
        }

        /* ---- Sidebar ---- */
        .sidebar {
            min-height: calc(100vh - 59px);
            background-color: #fff;
            border-right: 1px solid #e3e6ef;
        }
        .sidebar .nav-link {
            color: #3B4062;
            border-radius: .4rem;
            margin-bottom: .2rem;
            font-weight: 500;
        }
        .sidebar .nav-link:hover {
            background-color: #EEF0F9;
            color: var(--poste-navy);
        }
        .sidebar .nav-link.active {
            background-color: var(--poste-navy);
            color: #fff;
        }
        .sidebar .nav-link.active i { color: var(--poste-gold); }
        .sidebar-heading {
            font-size: .72rem;
            letter-spacing: .06em;
            color: #8A90AE;
        }

        /* ---- Boutons ---- */
        .btn-poste {
            background-color: var(--poste-navy);
            border-color: var(--poste-navy);
            color: #fff;
        }
        .btn-poste:hover {
            background-color: var(--poste-navy-dark);
            border-color: var(--poste-navy-dark);
            color: var(--poste-gold);
        }
        .btn-gold {
            background-color: var(--poste-gold);
            border-color: var(--poste-gold);
            color: var(--poste-navy);
            font-weight: 600;
        }
        .btn-gold:hover {
            background-color: var(--poste-gold-dark);
            border-color: var(--poste-gold-dark);
            color: var(--poste-navy);
        }

        /* ---- Cartes / tableaux ---- */
        .card { border: 1px solid #e3e6ef; border-radius: .6rem; }
        .card-header {
            background-color: #fff;
            border-bottom: 2px solid var(--poste-gold);
            font-weight: 600;
            color: var(--poste-navy);
        }
        .stat-card { border-top: 4px solid var(--poste-navy); }
        .stat-card .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--poste-navy);
        }
        .table > thead {
            background-color: var(--poste-navy);
            color: #fff;
        }
        .badge-navy { background-color: var(--poste-navy); color: #fff; }
        .badge-gold { background-color: var(--poste-gold); color: var(--poste-navy); }
        a { color: var(--poste-navy); }
        .page-title { color: var(--poste-navy); font-weight: 700; }
        .pagination .page-item.active .page-link {
            background-color: var(--poste-navy);
            border-color: var(--poste-navy);
        }
        .pagination .page-link { color: var(--poste-navy); }
    </style>
    @stack('styles')
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-poste">
    <div class="container-fluid">
        <a class="navbar-brand fw-semibold d-flex align-items-center" href="{{ route('dashboard') }}">
            <span class="brand-mark"><i class="bi bi-envelope-paper-fill"></i></span>
            {{ config('app.name') }}
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTop">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarTop">
            <ul class="navbar-nav ms-auto align-items-lg-center">
                @auth
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle me-1"></i>
                            {{ auth()->user()->prenom }} {{ auth()->user()->nom }}
                            <span class="badge badge-gold ms-1">{{ auth()->user()->role }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Mon profil</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">Déconnexion</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

<div class="container-fluid">
    <div class="row">
        @auth
        <aside class="col-lg-2 col-md-3 sidebar p-3">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                       href="{{ route('dashboard') }}">
                        <i class="bi bi-speedometer2 me-2"></i>Tableau de bord
                    </a>
                </li>

                {{-- Module Documents / Recherche / Suivi / Archivage : Membre 2 --}}
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('documents.*') ? 'active' : '' }}"
                       href="{{ Route::has('documents.index') ? route('documents.index') : '#' }}">
                        <i class="bi bi-file-earmark-text me-2"></i>Documents
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('recherche.*') ? 'active' : '' }}"
                       href="{{ Route::has('recherche.index') ? route('recherche.index') : '#' }}">
                        <i class="bi bi-search me-2"></i>Recherche
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('archivage.*') ? 'active' : '' }}"
                       href="{{ Route::has('archivage.index') ? route('archivage.index') : '#' }}">
                        <i class="bi bi-archive me-2"></i>Archivage
                    </a>
                </li>

                {{-- Reserve a l'administrateur --}}
                @if (auth()->user()->isAdmin())
                    <li class="nav-item mt-3 mb-1">
                        <span class="text-uppercase fw-semibold ps-2 sidebar-heading">Administration</span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}"
                           href="{{ route('users.index') }}">
                            <i class="bi bi-people me-2"></i>Utilisateurs
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('statistiques.*') ? 'active' : '' }}"
                           href="{{ route('statistiques.index') }}">
                            <i class="bi bi-bar-chart me-2"></i>Statistiques
                        </a>
                    </li>
                @endif
            </ul>
        </aside>
        @endauth

        <main class="@auth col-lg-10 col-md-9 @else col-12 @endauth p-4">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
