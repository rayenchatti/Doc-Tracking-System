<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Gestion Documentaire') }}</title>

    <!-- Bootstrap 5 CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f4f6f9;
        }
        .sidebar {
            min-height: calc(100vh - 56px);
            background-color: #1e293b;
            color: #f8fafc;
        }
        .sidebar .nav-link {
            color: #94a3b8;
            font-weight: 500;
            padding: 0.75rem 1.25rem;
            border-radius: 0.375rem;
            margin-bottom: 0.25rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.2s ease-in-out;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            color: #ffffff;
            background-color: #334155;
        }
        .sidebar .nav-link i {
            font-size: 1.1rem;
        }
        .sidebar-heading {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #64748b;
            padding: 0.75rem 1.25rem 0.25rem;
            font-weight: 700;
        }
        .main-content {
            padding: 2rem;
        }
        .navbar-brand-title {
            font-weight: 700;
            color: #1e293b;
        }
        .badge-role {
            font-size: 0.7rem;
            text-transform: uppercase;
            padding: 0.3em 0.6em;
        }
    </style>
</head>
<body>
    <!-- Top Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center gap-2 ms-2" href="{{ route('dashboard') }}">
                <i class="bi bi-file-earmark-text-fill text-primary fs-4"></i>
                <span class="navbar-brand-title">GestionDoc PFE</span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#topNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="topNavbar">
                <ul class="navbar-fluid ms-auto navbar-nav align-items-center gap-3 me-3">
                    @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle fs-5 text-secondary"></i>
                                <span>{{ Auth::user()->prenom }} {{ Auth::user()->nom }}</span>
                                <span class="badge {{ Auth::user()->isAdmin() ? 'bg-danger' : 'bg-primary' }} badge-role">
                                    {{ Auth::user()->role }}
                                </span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="bi bi-person me-2"></i> Mon Profil</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i> Déconnexion</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Container with Sidebar -->
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse p-3">
                <div class="position-sticky">
                    <div class="sidebar-heading">Navigation</div>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                                <i class="bi bi-speedometer2"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>

                        @if(Auth::check() && Auth::user()->isAdmin())
                            <div class="sidebar-heading">Administration</div>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('users*') ? 'active' : '' }}" href="{{ url('/users') }}">
                                    <i class="bi bi-people-fill"></i>
                                    <span>Utilisateurs</span>
                                </a>
                            </li>
                        @endif

                        <div class="sidebar-heading">Gestion Documentaire</div>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('documents*') ? 'active' : '' }}" href="{{ url('/documents') }}">
                                <i class="bi bi-folder-fill"></i>
                                <span>Documents</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('recherche*') ? 'active' : '' }}" href="{{ url('/recherche') }}">
                                <i class="bi bi-search"></i>
                                <span>Recherche</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('archivage*') ? 'active' : '' }}" href="{{ url('/archivage') }}">
                                <i class="bi bi-archive-fill"></i>
                                <span>Archivage</span>
                            </a>
                        </li>

                        @if(Auth::check() && Auth::user()->isAdmin())
                            <div class="sidebar-heading">Rapports</div>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('statistiques*') ? 'active' : '' }}" href="{{ url('/statistiques') }}">
                                    <i class="bi bi-bar-chart-fill"></i>
                                    <span>Statistiques</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
            </nav>

            <!-- Main Content Area -->
            <main class="col-md-9 ms-sm-auto col-lg-10 main-content">
                @isset($header)
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-2 pb-3 mb-4 border-bottom">
                        <h1 class="h2 text-dark font-weight-bold">{{ $header }}</h1>
                    </div>
                @endisset

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                {{ $slot }}
            </main>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
