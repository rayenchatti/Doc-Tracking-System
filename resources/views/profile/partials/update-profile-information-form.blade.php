<div class="card h-100">
    <div class="card-header">
        <i class="bi bi-person-lines-fill me-1"></i> Informations personnelles
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('profile.update') }}">
            @csrf
            @method('PATCH')

            <div class="row g-3">
                <div class="col-md-6">
                    <label for="nom" class="form-label fw-semibold">Nom <span class="text-danger">*</span></label>
                    <input type="text" id="nom" name="nom"
                           class="form-control @error('nom') is-invalid @enderror"
                           value="{{ old('nom', $user->nom) }}" required>
                    @error('nom') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label for="prenom" class="form-label fw-semibold">Prénom <span class="text-danger">*</span></label>
                    <input type="text" id="prenom" name="prenom"
                           class="form-control @error('prenom') is-invalid @enderror"
                           value="{{ old('prenom', $user->prenom) }}" required>
                    @error('prenom') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label for="email" class="form-label fw-semibold">E-mail <span class="text-danger">*</span></label>
                    <input type="email" id="email" name="email"
                           class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email', $user->email) }}" required>
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label for="telephone" class="form-label fw-semibold">Téléphone</label>
                    <input type="text" id="telephone" name="telephone"
                           class="form-control @error('telephone') is-invalid @enderror"
                           value="{{ old('telephone', $user->telephone) }}">
                    @error('telephone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            {{-- Le role et le statut ne sont pas modifiables ici :
                 seul un administrateur peut les changer, via /users. --}}
            <div class="row g-3 mt-1">
                <div class="col-md-6">
                    <label class="form-label fw-semibold text-muted">Rôle</label>
                    <input type="text" class="form-control" value="{{ $user->role }}" disabled>
                    <div class="form-text">Modifiable uniquement par un administrateur.</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold text-muted">Statut du compte</label>
                    <input type="text" class="form-control" value="{{ $user->statut }}" disabled>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-gold">
                    <i class="bi bi-check-lg me-1"></i> Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>
