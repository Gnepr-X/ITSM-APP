@extends('layouts.app')
@section('title', 'Nouveau ticket')
@section('content')

<div class="row justify-content-center">
  <div class="col-md-9">
    <div class="card">
      <div class="card-header fw-semibold">
        <i class="bi bi-tools me-2"></i>Nouveau ticket d'intervention
      </div>
      <div class="card-body">
        <form method="POST" action="{{ route('interventions.store') }}">
          @csrf
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label fw-medium">Site <span class="text-danger">*</span></label>
              <select name="site_id" class="form-select @error('site_id') is-invalid @enderror" required>
                <option value="">Sélectionner...</option>
                @foreach($sites as $s)
                  <option value="{{ $s->id }}" {{ old('site_id') == $s->id ? 'selected' : '' }}>
                    {{ $s->nom }} — {{ $s->ville }}
                  </option>
                @endforeach
              </select>
              @error('site_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-3">
              <label class="form-label fw-medium">Type <span class="text-danger">*</span></label>
              <select name="type" class="form-select" required>
                @foreach(['depannage','installation','maintenance','support','autre'] as $t)
                  <option value="{{ $t }}" {{ old('type') === $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-3">
              <label class="form-label fw-medium">Priorité <span class="text-danger">*</span></label>
              <select name="priorite" class="form-select" required>
                <option value="basse" {{ old('priorite') === 'basse' ? 'selected' : '' }}>Basse</option>
                <option value="normale" {{ old('priorite','normale') === 'normale' ? 'selected' : '' }}>Normale</option>
                <option value="haute" {{ old('priorite') === 'haute' ? 'selected' : '' }}>Haute</option>
                <option value="critique" {{ old('priorite') === 'critique' ? 'selected' : '' }}>🔴 Critique</option>
              </select>
            </div>
            <div class="col-12">
              <label class="form-label fw-medium">Titre <span class="text-danger">*</span></label>
              <input type="text" name="titre" class="form-control @error('titre') is-invalid @enderror"
                     value="{{ old('titre') }}" placeholder="Résumé court du problème..." required>
              @error('titre')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
              <label class="form-label fw-medium">Description détaillée <span class="text-danger">*</span></label>
              <textarea name="description" class="form-control @error('description') is-invalid @enderror"
                        rows="4" placeholder="Décrire le problème en détail..." required>{{ old('description') }}</textarea>
              @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
              <label class="form-label fw-medium">Ressource concernée</label>
              <select name="ressource_id" class="form-select">
                <option value="">Aucune / Non défini</option>
                @foreach($ressources as $r)
                  <option value="{{ $r->id }}" {{ old('ressource_id') == $r->id ? 'selected' : '' }}>
                    {{ $r->nom_complet }} — {{ $r->site->nom }}
                  </option>
                @endforeach
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-medium">Équipement concerné</label>
              <select name="equipement_id" class="form-select">
                <option value="">Aucun / Non défini</option>
                @foreach($equipements as $eq)
                  <option value="{{ $eq->id }}" {{ old('equipement_id') == $eq->id ? 'selected' : '' }}>
                    [{{ $eq->code_inventaire }}] {{ $eq->designation }}
                  </option>
                @endforeach
              </select>
            </div>
            <div class="col-md-4">
              <label class="form-label fw-medium">Date d'ouverture <span class="text-danger">*</span></label>
              <input type="date" name="date_ouverture" class="form-control"
                     value="{{ old('date_ouverture', date('Y-m-d')) }}" required>
            </div>
          </div>
          <hr>
          <div class="d-flex gap-2 justify-content-end">
            <a href="{{ route('interventions.index') }}" class="btn btn-outline-secondary">Annuler</a>
            <button type="submit" class="btn btn-primary">
              <i class="bi bi-check-lg me-1"></i>Ouvrir le ticket
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection