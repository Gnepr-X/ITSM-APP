@extends('layouts.app')
@section('title', 'Nouvelle caméra')
@section('content')

<div class="row justify-content-center">
  <div class="col-md-7">
    <div class="card">
      <div class="card-header fw-semibold">
        <i class="bi bi-camera-video-fill me-2"></i>Nouvelle caméra
      </div>
      <div class="card-body">
        <form method="POST" action="{{ route('cameras.store') }}">
          @csrf
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label fw-medium">Site <span class="text-danger">*</span></label>
              <select name="site_id"
                      class="form-select @error('site_id') is-invalid @enderror" required>
                <option value="">Sélectionner un site...</option>
                @foreach($sites as $s)
                  <option value="{{ $s->id }}"
                    {{ old('site_id') == $s->id ? 'selected' : '' }}>
                    {{ $s->nom }} — {{ $s->ville }}
                  </option>
                @endforeach
              </select>
              @error('site_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
              <label class="form-label fw-medium">Référence <span class="text-danger">*</span></label>
              <input type="text" name="reference"
                     class="form-control @error('reference') is-invalid @enderror"
                     value="{{ old('reference') }}"
                     placeholder="Ex: CAM-001" required>
              @error('reference')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
              <label class="form-label fw-medium">Marque</label>
              <input type="text" name="marque" class="form-control"
                     value="{{ old('marque') }}"
                     placeholder="Ex: Hikvision, Dahua, Axis...">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-medium">Modèle</label>
              <input type="text" name="modele" class="form-control"
                     value="{{ old('modele') }}"
                     placeholder="Ex: DS-2CD2143G2-I">
            </div>
            <div class="col-12">
              <label class="form-label fw-medium">Emplacement <span class="text-danger">*</span></label>
              <input type="text" name="emplacement"
                     class="form-control @error('emplacement') is-invalid @enderror"
                     value="{{ old('emplacement') }}"
                     placeholder="Ex: Entrée principale, Parking nord, Couloir RDC..." required>
              @error('emplacement')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
              <label class="form-label fw-medium">Adresse IP</label>
              <input type="text" name="adresse_ip"
                     class="form-control @error('adresse_ip') is-invalid @enderror"
                     value="{{ old('adresse_ip') }}"
                     placeholder="Ex: 192.168.1.101">
              @error('adresse_ip')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-3">
              <label class="form-label fw-medium">Date d'installation</label>
              <input type="date" name="date_installation" class="form-control"
                     value="{{ old('date_installation') }}">
            </div>
            <div class="col-md-3">
              <label class="form-label fw-medium">Statut <span class="text-danger">*</span></label>
              <select name="statut" class="form-select" required>
                <option value="actif"    {{ old('statut','actif') === 'actif'    ? 'selected' : '' }}>Actif</option>
                <option value="inactif"  {{ old('statut') === 'inactif'  ? 'selected' : '' }}>Inactif</option>
                <option value="en_panne" {{ old('statut') === 'en_panne' ? 'selected' : '' }}>En panne</option>
              </select>
            </div>
          </div>
          <hr>
          <div class="d-flex gap-2 justify-content-end">
            <a href="{{ route('cameras.index') }}" class="btn btn-outline-secondary">Annuler</a>
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