@extends('layouts.app')
@section('title', 'Modifier le ticket')
@section('content')

<div class="row justify-content-center">
  <div class="col-md-9">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <span class="fw-semibold">
          <i class="bi bi-pencil me-2"></i>Modifier — {{ $intervention->numero_ticket }}
        </span>
        <span class="badge bg-{{ $intervention->priorite_badge }}">
          {{ ucfirst($intervention->priorite) }}
        </span>
      </div>
      <div class="card-body">
        <form method="POST" action="{{ route('interventions.update', $intervention) }}">
          @csrf @method('PUT')
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label fw-medium">Site <span class="text-danger">*</span></label>
              <select name="site_id" class="form-select @error('site_id') is-invalid @enderror" required>
                @foreach($sites as $s)
                  <option value="{{ $s->id }}"
                    {{ old('site_id', $intervention->site_id) == $s->id ? 'selected' : '' }}>
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
                  <option value="{{ $t }}"
                    {{ old('type', $intervention->type) === $t ? 'selected' : '' }}>
                    {{ ucfirst($t) }}
                  </option>
                @endforeach
              </select>
            </div>
            <div class="col-md-3">
              <label class="form-label fw-medium">Priorité <span class="text-danger">*</span></label>
              <select name="priorite" class="form-select" required>
                @foreach(['basse','normale','haute','critique'] as $p)
                  <option value="{{ $p }}"
                    {{ old('priorite', $intervention->priorite) === $p ? 'selected' : '' }}>
                    {{ $p === 'critique' ? '🔴 ' : '' }}{{ ucfirst($p) }}
                  </option>
                @endforeach
              </select>
            </div>
            <div class="col-md-9">
              <label class="form-label fw-medium">Titre <span class="text-danger">*</span></label>
              <input type="text" name="titre"
                     class="form-control @error('titre') is-invalid @enderror"
                     value="{{ old('titre', $intervention->titre) }}" required>
              @error('titre')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-3">
              <label class="form-label fw-medium">Statut <span class="text-danger">*</span></label>
              <select name="statut" class="form-select" required>
                @foreach(['ouvert','en_cours','resolu','ferme'] as $s)
                  <option value="{{ $s }}"
                    {{ old('statut', $intervention->statut) === $s ? 'selected' : '' }}>
                    {{ ucfirst($s) }}
                  </option>
                @endforeach
              </select>
            </div>
            <div class="col-12">
              <label class="form-label fw-medium">Description <span class="text-danger">*</span></label>
              <textarea name="description"
                        class="form-control @error('description') is-invalid @enderror"
                        rows="3" required>{{ old('description', $intervention->description) }}</textarea>
              @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
              <label class="form-label fw-medium">Ressource concernée</label>
              <select name="ressource_id" class="form-select">
                <option value="">Aucune</option>
                @foreach($ressources as $r)
                  <option value="{{ $r->id }}"
                    {{ old('ressource_id', $intervention->ressource_id) == $r->id ? 'selected' : '' }}>
                    {{ $r->nom_complet }} — {{ $r->site->nom }}
                  </option>
                @endforeach
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-medium">Équipement concerné</label>
              <select name="equipement_id" class="form-select">
                <option value="">Aucun</option>
                @foreach($equipements as $eq)
                  <option value="{{ $eq->id }}"
                    {{ old('equipement_id', $intervention->equipement_id) == $eq->id ? 'selected' : '' }}>
                    [{{ $eq->code_inventaire }}] {{ $eq->designation }}
                  </option>
                @endforeach
              </select>
            </div>
            <div class="col-md-4">
              <label class="form-label fw-medium">Date d'ouverture <span class="text-danger">*</span></label>
              <input type="date" name="date_ouverture" class="form-control"
                     value="{{ old('date_ouverture', $intervention->date_ouverture->format('Y-m-d')) }}" required>
            </div>
            <div class="col-md-4">
              <label class="form-label fw-medium">Date de résolution</label>
              <input type="date" name="date_resolution" class="form-control"
                     value="{{ old('date_resolution', $intervention->date_resolution?->format('Y-m-d')) }}">
            </div>
            <div class="col-12">
              <label class="form-label fw-medium">Solution apportée</label>
              <textarea name="solution" class="form-control" rows="3"
                        placeholder="Décrire la solution mise en place...">{{ old('solution', $intervention->solution) }}</textarea>
            </div>
          </div>
          <hr>
          <div class="d-flex gap-2 justify-content-end">
            <a href="{{ route('interventions.index') }}" class="btn btn-outline-secondary">Annuler</a>
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