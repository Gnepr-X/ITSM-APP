@extends('layouts.app')
@section('title', 'Nouvelle ressource')
@section('content')

<div class="row justify-content-center">
  <div class="col-md-8">
    <div class="card">
      <div class="card-header fw-semibold">
        <i class="bi bi-person-plus me-2"></i>Nouvelle ressource
      </div>
      <div class="card-body">
        <form method="POST" action="{{ route('ressources.store') }}">
          @csrf
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label fw-medium">Nom <span class="text-danger">*</span></label>
              <input type="text" name="nom"
                     class="form-control @error('nom') is-invalid @enderror"
                     value="{{ old('nom') }}"
                     placeholder="Ex: KOUASSI" required>
              @error('nom')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
              <label class="form-label fw-medium">Prénom <span class="text-danger">*</span></label>
              <input type="text" name="prenom"
                     class="form-control @error('prenom') is-invalid @enderror"
                     value="{{ old('prenom') }}"
                     placeholder="Ex: Jean-Marc" required>
              @error('prenom')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
              <label class="form-label fw-medium">Matricule <span class="text-danger">*</span></label>
              <input type="text" name="matricule"
                     class="form-control @error('matricule') is-invalid @enderror"
                     value="{{ old('matricule') }}"
                     placeholder="Ex: EMP-2024-001" required>
              @error('matricule')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
              <label class="form-label fw-medium">Poste <span class="text-danger">*</span></label>
              <input type="text" name="poste"
                     class="form-control @error('poste') is-invalid @enderror"
                     value="{{ old('poste') }}"
                     placeholder="Ex: Comptable, Directeur..." required>
              @error('poste')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
              <label class="form-label fw-medium">Département</label>
              <input type="text" name="departement" class="form-control"
                     value="{{ old('departement') }}"
                     placeholder="Ex: Finance, RH, Informatique...">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-medium">Site <span class="text-danger">*</span></label>
              <select name="site_id"
                      class="form-select @error('site_id') is-invalid @enderror" required>
                <option value="">Sélectionner un site...</option>
                @foreach($sites as $site)
                  <option value="{{ $site->id }}"
                    {{ old('site_id') == $site->id ? 'selected' : '' }}>
                    {{ $site->nom }} — {{ $site->ville }}
                  </option>
                @endforeach
              </select>
              @error('site_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
              <label class="form-label fw-medium">Email</label>
              <input type="email" name="email"
                     class="form-control @error('email') is-invalid @enderror"
                     value="{{ old('email') }}"
                     placeholder="prenom.nom@entreprise.com">
              @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
              <label class="form-label fw-medium">Téléphone</label>
              <input type="text" name="telephone" class="form-control"
                     value="{{ old('telephone') }}"
                     placeholder="Ex: +225 07 00 00 00 00">
            </div>
          </div>
          <hr>
          <div class="d-flex gap-2 justify-content-end">
            <a href="{{ route('ressources.index') }}" class="btn btn-outline-secondary">Annuler</a>
            <button type="submit" class="btn btn-primary">
              <i class="bi bi-check-lg me-1"></i>Enregistrer
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection