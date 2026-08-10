{{-- Champs communs aux formulaires de creation et de modification --}}
@php($isEdit = isset($user))

<div class="row g-3">
    <div class="col-md-6">
        <label for="nom" class="form-label fw-semibold">Nom <span class="text-danger">*</span></label>
        <input type="text" id="nom" name="nom"
               class="form-control @error('nom') is-invalid @enderror"
               value="{{ old('nom', $isEdit ? $user->nom : '') }}" required>
        @error('nom') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6">
        <label for="prenom" class="form-label fw-semibold">Prénom <span class="text-danger">*</span></label>
        <input type="text" id="prenom" name="prenom"
               class="form-control @error('prenom') is-invalid @enderror"
               value="{{ old('prenom', $isEdit ? $user->prenom : '') }}" required>
        @error('prenom') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6">
        <label for="email" class="form-label fw-semibold">E-mail <span class="text-danger">*</span></label>
        <input type="email" id="email" name="email"
               class="form-control @error('email') is-invalid @enderror"
               value="{{ old('email', $isEdit ? $user->email : '') }}" required>
        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6">
        <label for="telephone" class="form-label fw-semibold">Téléphone</label>
        <input type="text" id="telephone" name="telephone"
               class="form-control @error('telephone') is-invalid @enderror"
               value="{{ old('telephone', $isEdit ? $user->telephone : '') }}">
        @error('telephone') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6">
        <label for="password" class="form-label fw-semibold">
            Mot de passe
            @if ($isEdit)
                <small class="text-muted fw-normal">(laisser vide pour ne pas changer)</small>
            @else
                <span class="text-danger">*</span>
            @endif
        </label>
        <input type="password" id="password" name="password"
               class="form-control @error('password') is-invalid @enderror"
               {{ $isEdit ? '' : 'required' }}>
        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6">
        <label for="password_confirmation" class="form-label fw-semibold">Confirmer le mot de passe</label>
        <input type="password" id="password_confirmation" name="password_confirmation"
               class="form-control" {{ $isEdit ? '' : 'required' }}>
    </div>

    @php($estMoiMeme = $isEdit && $user->id === auth()->id())

    <div class="col-md-6">
        <label for="role" class="form-label fw-semibold">Rôle <span class="text-danger">*</span></label>
        <select id="role" name="role" class="form-select @error('role') is-invalid @enderror"
                {{ $estMoiMeme ? 'disabled' : '' }} required>
            <option value="agent" @selected(old('role', $isEdit ? $user->role : 'agent') === 'agent')>Agent</option>
            <option value="admin" @selected(old('role', $isEdit ? $user->role : '') === 'admin')>Administrateur</option>
        </select>
        @if ($estMoiMeme)
            <input type="hidden" name="role" value="{{ $user->role }}">
            <div class="form-text">Vous ne pouvez pas modifier votre propre rôle.</div>
        @endif
        @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6">
        <label for="statut" class="form-label fw-semibold">Statut du compte <span class="text-danger">*</span></label>
        <select id="statut" name="statut" class="form-select @error('statut') is-invalid @enderror"
                {{ $estMoiMeme ? 'disabled' : '' }} required>
            <option value="actif" @selected(old('statut', $isEdit ? $user->statut : 'actif') === 'actif')>Actif</option>
            <option value="desactive" @selected(old('statut', $isEdit ? $user->statut : '') === 'desactive')>Désactivé</option>
        </select>
        @if ($estMoiMeme)
            <input type="hidden" name="statut" value="{{ $user->statut }}">
            <div class="form-text">Vous ne pouvez pas désactiver votre propre compte.</div>
        @endif
        @error('statut') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>
