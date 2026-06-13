@extends('layouts.app')
@section('title', 'Nouvelle attribution')
@section('content')

<div class="row justify-content-center">
  <div class="col-md-8">
    <div class="card">
      <div class="card-header fw-semibold">
        <i class="bi bi-box-arrow-in-right me-2"></i>Nouvelle attribution
      </div>
      <div class="card-body">
        <form method="POST" action="{{ route('attributions.store') }}">
          @csrf
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label fw-medium">Équipement <span class="text-danger">*</span></label>
              <select name="equipement_id" class="form-select @error('equipement_id') is-invalid @enderror" required>
                <option value="">Sélectionner un équipement disponible...</option>
                @foreach($equipements as $eq)
                  <option value="{{ $eq->id }}"
                    {{ old('equipement_id', request('equipement_id')) == $eq->id ? 'selected' : '' }}>
                    [{{ $eq->code_inventaire }}] {{ $eq->designation }} — {{ $eq->site->nom }}
                  </option>
                @endforeach
              </select>
              @error('equipement_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
              <label class="form-label fw-medium">Bénéficiaire <span class="text-danger">*</span></label>
              <select name="ressource_id" class="form-select @error('ressource_id') is-invalid @enderror" required>
                <option value="">Sélectionner une ressource...</option>
                @foreach($ressources as $r)
                  <option value="{{ $r->id }}" {{ old('ressource_id') == $r->id ? 'selected' : '' }}>
                    {{ $r->nom_complet }} ({{ $r->matricule }}) — {{ $r->site->nom }}
                  </option>
                @endforeach
              </select>
              @error('ressource_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
              <label class="form-label fw-medium">Site d'attribution <span class="text-danger">*</span></label>
              <select name="site_id" class="form-select @error('site_id') is-invalid @enderror" required>
                <option value="">Sélectionner un site...</option>
                @foreach($sites as $s)
                  <option value="{{ $s->id }}" {{ old('site_id') == $s->id ? 'selected' : '' }}>
                    {{ $s->nom }} — {{ $s->ville }}
                  </option>
                @endforeach
              </select>
              @error('site_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-3">
              <label class="form-label fw-medium">Date d'attribution <span class="text-danger">*</span></label>
              <input type="date" name="date_attribution" class="form-control" value="{{ old('date_attribution', date('Y-m-d')) }}" required>
            </div>
            <div class="col-md-3">
              <label class="form-label fw-medium">Attribué par <span class="text-danger">*</span></label>
              <input type="text" name="attribue_par" class="form-control"
                     value="{{ old('attribue_par', auth()->user()->name) }}" required>
            </div>
            <div class="col-12">
              <label class="form-label fw-medium">Observations</label>
              <textarea name="observation" class="form-control" rows="2"
                        placeholder="État du matériel, accessoires inclus...">{{ old('observation') }}</textarea>
            </div>
          </div>
          <hr>
          <div class="d-flex gap-2 justify-content-end">
            <a href="{{ route('attributions.index') }}" class="btn btn-outline-secondary">Annuler</a>
            <button type="submit" class="btn btn-primary">
              <i class="bi bi-check-lg me-1"></i>Valider l'attribution
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection