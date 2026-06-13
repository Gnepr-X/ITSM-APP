@extends('layouts.app')
@section('title', 'Modifier le site')
@section('content')

<div class="row justify-content-center">
  <div class="col-md-7">
    <div class="card">
      <div class="card-header">
        <span class="fw-semibold"><i class="bi bi-pencil me-2"></i>Modifier — {{ $site->nom }}</span>
      </div>
      <div class="card-body">
        <form method="POST" action="{{ route('sites.update', $site) }}">
          @csrf @method('PUT')
          <div class="mb-3">
            <label class="form-label fw-medium">Nom du site <span class="text-danger">*</span></label>
            <input type="text" name="nom" class="form-control @error('nom') is-invalid @enderror"
                   value="{{ old('nom', $site->nom) }}" required>
            @error('nom')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="mb-3">
            <label class="form-label fw-medium">Ville <span class="text-danger">*</span></label>
            <input type="text" name="ville" class="form-control" value="{{ old('ville', $site->ville) }}" required>
          </div>
          <div class="mb-3">
            <label class="form-label fw-medium">Adresse</label>
            <textarea name="adresse" class="form-control" rows="2">{{ old('adresse', $site->adresse) }}</textarea>
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label fw-medium">Responsable</label>
              <input type="text" name="responsable" class="form-control" value="{{ old('responsable', $site->responsable) }}">
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label fw-medium">Téléphone</label>
              <input type="text" name="telephone" class="form-control" value="{{ old('telephone', $site->telephone) }}">
            </div>
          </div>
          <div class="d-flex gap-2 justify-content-end">
            <a href="{{ route('sites.index') }}" class="btn btn-outline-secondary">Annuler</a>
            <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Mettre à jour</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection