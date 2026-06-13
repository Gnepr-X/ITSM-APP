@extends('layouts.app')
@section('title', 'Modifier — '.$ressource->nom_complet)
@section('content')

<div class="row justify-content-center">
  <div class="col-md-8">
    <div class="card">
      <div class="card-header fw-semibold">
        <i class="bi bi-pencil me-2"></i>Modifier — {{ $ressource->nom_complet }}
      </div>
      <div class="card-body">
        <form method="POST" action="{{ route('ressources.update', $ressource) }}">
          @csrf @method('PUT')
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label fw-medium">Nom <span class="text-danger">*</span></label>
              <input type="text" name="nom" class="form-control @error('nom') is-invalid @enderror"
                     value="{{ old('nom', $ressource->nom) }}" required>
              @error('nom')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
              <label class="form-label fw-medium">Prénom <span class="text-danger">*</span></label>
              <input type="text" name="prenom" class="form-control @error('prenom') is-invalid @enderror"
                     value="{{ old('prenom', $ressource->prenom) }}" required>
              @error('prenom')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
              <label class="form-label fw-medium">Matricule <span class="text-danger">*</span></label>
              <input type="text" name="matricule"
                     class="form-control @error('matricule') is-invalid @enderror"
                     value="{{ old('matricule', $ressource->matricule) }}" required>
              @error('matricule')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
              <label class="form-label fw-medium">Poste <span class="text-danger">*</span></label>
              <input type="text" name="poste" class="form-control @error('poste') is-invalid @enderror"
                     value="{{ old('poste', $ressource->poste) }}" required>
              @error('poste')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
              <label class="form-label fw-medium">Département</label>
              <input type="text" name="departement" class="form-control"
                     value="{{ old('departement', $ressource->departement) }}"
                     placeholder="Ex: Comptabilité">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-medium">Site <span class="text-danger">*</span></label>
              <select name="site_id" class="form-select @error('site_id') is-invalid @enderror" required>
                <option value="">Sélectionner un site...</option>
                @foreach($sites as $site)
                  <option value="{{ $site->id }}"
                    {{ old('site_id', $ressource->site_id) == $site->id ? 'selected' : '' }}>
                    {{ $site->nom }} — {{ $site->ville }}
                  </option>
                @endforeach
              </select>
              @error('site_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
              <label class="form-label fw-medium">Email</label>
              <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                     value="{{ old('email', $ressource->email) }}">
              @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
              <label class="form-label fw-medium">Téléphone</label>
              <input type="text" name="telephone" class="form-control"
                     value="{{ old('telephone', $ressource->telephone) }}">
            </div>
          </div>
          <hr>
          <div class="d-flex gap-2 justify-content-end">
            <a href="{{ route('ressources.index') }}" class="btn btn-outline-secondary">Annuler</a>
            <button type="submit" class="btn btn-primary">
              <i class="bi bi-check-lg me-1"></i>Mettre à jour
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection