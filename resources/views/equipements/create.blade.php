@extends('layouts.app')
@section('title', 'Nouvel équipement')
@section('content')

<div class="row justify-content-center">
  <div class="col-md-8">
    <div class="card">
      <div class="card-header fw-semibold">
        <i class="bi bi-laptop me-2"></i>Nouvel équipement
      </div>
      <div class="card-body">
        <form method="POST" action="{{ route('equipements.store') }}">
          @csrf
          <div class="row g-3">
            <div class="col-md-8">
              <label class="form-label fw-medium">Désignation <span class="text-danger">*</span></label>
              <input type="text" name="designation" class="form-control @error('designation') is-invalid @enderror"
                     value="{{ old('designation') }}" placeholder="Ex: Dell Latitude 5540" required>
              @error('designation')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
              <label class="form-label fw-medium">Type <span class="text-danger">*</span></label>
              <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                <option value="">Sélectionner...</option>
                @foreach(['ordinateur','imprimante','serveur','switch','routeur','camera','alarme','autre'] as $t)
                  <option value="{{ $t }}" {{ old('type') === $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
                @endforeach
              </select>
              @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
              <label class="form-label fw-medium">Marque</label>
              <input type="text" name="marque" class="form-control" value="{{ old('marque') }}" placeholder="Ex: Dell">
            </div>
            <div class="col-md-4">
              <label class="form-label fw-medium">Modèle</label>
              <input type="text" name="modele" class="form-control" value="{{ old('modele') }}" placeholder="Ex: Latitude 5540">
            </div>
            <div class="col-md-4">
              <label class="form-label fw-medium">Numéro de série</label>
              <input type="text" name="numero_serie" class="form-control @error('numero_serie') is-invalid @enderror"
                     value="{{ old('numero_serie') }}">
              @error('numero_serie')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
              <label class="form-label fw-medium">Site <span class="text-danger">*</span></label>
              <select name="site_id" class="form-select @error('site_id') is-invalid @enderror" required>
                <option value="">Sélectionner un site...</option>
                @foreach($sites as $site)
                  <option value="{{ $site->id }}" {{ old('site_id') == $site->id ? 'selected' : '' }}>
                    {{ $site->nom }} — {{ $site->ville }}
                  </option>
                @endforeach
              </select>
              @error('site_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-3">
              <label class="form-label fw-medium">Date d'acquisition</label>
              <input type="date" name="date_acquisition" class="form-control" value="{{ old('date_acquisition') }}">
            </div>
            <div class="col-md-3">
              <label class="form-label fw-medium">Valeur (FCFA)</label>
              <input type="number" name="valeur" class="form-control" value="{{ old('valeur') }}" min="0">
            </div>
            <div class="col-12">
              <label class="form-label fw-medium">Notes</label>
              <textarea name="notes" class="form-control" rows="8" placeholder="Observations, accessoires inclus...">{{ old('notes') }}</textarea>
            </div>
          </div>
          <hr>
          <div class="alert alert-info d-flex align-items-center" style="font-size:13px">
            <i class="bi bi-info-circle me-2"></i>
            Le code d'inventaire et le QR code seront générés automatiquement à l'enregistrement.
          </div>
          <div class="d-flex gap-2 justify-content-end">
            <a href="{{ route('equipements.index') }}" class="btn btn-outline-secondary">Annuler</a>
            <button type="submit" class="btn btn-primary">
              <i class="bi bi-check-lg me-1"></i>Enregistrer & générer QR
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection