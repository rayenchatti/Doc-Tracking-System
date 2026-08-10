<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Connexion - {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --poste-navy: #1B2073;
            --poste-navy-dark: #141855;
            --poste-gold: #F5C518;
            --poste-gold-dark: #D9A900;
        }
        body {
            background: linear-gradient(135deg, var(--poste-navy) 0%, var(--poste-navy-dark) 100%);
            min-height: 100vh;
        }
        .login-card {
            border: none;
            border-radius: .9rem;
            border-top: 5px solid var(--poste-gold);
            box-shadow: 0 18px 45px rgba(0, 0, 0, .3);
        }
        /* Losange dore inspire de l'identite visuelle de La Poste */
        .brand-diamond {
            width: 74px;
            height: 74px;
            background-color: var(--poste-navy);
            transform: rotate(45deg);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.4rem;
            border: 4px solid var(--poste-gold);
        }
        .brand-diamond i {
            transform: rotate(-45deg);
            color: var(--poste-gold);
            font-size: 1.9rem;
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
        .form-control:focus {
            border-color: var(--poste-navy);
            box-shadow: 0 0 0 .2rem rgba(27, 32, 115, .15);
        }
        .app-title { color: var(--poste-navy); font-weight: 700; }
    </style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="col-md-6 col-lg-4">
            <div class="card login-card">
                <div class="card-body p-4 p-lg-5">
                    <div class="text-center mb-4">
                        <div class="brand-diamond">
                            <i class="bi bi-envelope-paper-fill"></i>
                        </div>
                        <h1 class="h4 app-title mb-1">{{ config('app.name') }}</h1>
                        <p class="text-muted small mb-0">Gestion et suivi des documents administratifs</p>
                    </div>

                    @if (session('status'))
                        <div class="alert alert-success">{{ session('status') }}</div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0 ps-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">Adresse e-mail</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-envelope"></i></span>
                                <input id="email" type="email" name="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       value="{{ old('email') }}" required autofocus>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold">Mot de passe</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-lock"></i></span>
                                <input id="password" type="password" name="password"
                                       class="form-control @error('password') is-invalid @enderror" required>
                            </div>
                        </div>

                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember">
                            <label class="form-check-label small" for="remember">Se souvenir de moi</label>
                        </div>

                        <button type="submit" class="btn btn-gold w-100 py-2">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Se connecter
                        </button>
                    </form>
                </div>
            </div>
            <p class="text-center text-white-50 small mt-3 mb-0">
                Projet de Fin d'Études &mdash; {{ date('Y') }}
            </p>
        </div>
    </div>
</div>
</body>
</html>
