<div class="card h-100">
    <div class="card-header">
        <i class="bi bi-shield-lock me-1"></i> Modifier le mot de passe
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="update_password_current_password" class="form-label fw-semibold">
                    Mot de passe actuel <span class="text-danger">*</span>
                </label>
                <input type="password" id="update_password_current_password" name="current_password"
                       class="form-control @error('current_password', 'updatePassword') is-invalid @enderror"
                       autocomplete="current-password" required>
                @error('current_password', 'updatePassword')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="update_password_password" class="form-label fw-semibold">
                    Nouveau mot de passe <span class="text-danger">*</span>
                </label>
                <input type="password" id="update_password_password" name="password"
                       class="form-control @error('password', 'updatePassword') is-invalid @enderror"
                       autocomplete="new-password" required>
                @error('password', 'updatePassword')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="update_password_password_confirmation" class="form-label fw-semibold">
                    Confirmer le nouveau mot de passe <span class="text-danger">*</span>
                </label>
                <input type="password" id="update_password_password_confirmation" name="password_confirmation"
                       class="form-control @error('password_confirmation', 'updatePassword') is-invalid @enderror"
                       autocomplete="new-password" required>
                @error('password_confirmation', 'updatePassword')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-poste">
                <i class="bi bi-key me-1"></i> Modifier le mot de passe
            </button>
        </form>
    </div>
</div>
