@extends('layouts.app')
@section('title', 'Nouvelle restitution')
@section('content')

<div class="row justify-content-center">
  <div class="col-md-8">
    <div class="card">
      <div class="card-header fw-semibold">
        <i class="bi bi-box-arrow-left me-2"></i>Enregistrer une restitution
      </div>
      <div class="card-body">

        @if($attribution)
        <!-- Pré-rempli depuis une attribution spécifique -->
        <div class="alert alert-info d-flex align-items-start gap-3 mb-4" style="font-size:13.5px">
          <i class="bi bi-info-circle-fill mt-1"></i>
          <div>
            <strong>{{ $attribution->equipement->designation }}</strong>
            <span class="text-muted"> [{{ $attribution->equipement->code_inventaire }}]</span><br>
            Attribué à <strong>{{ $attribution->ressource->nom_complet }}</strong>
            depuis le {{ $attribution->date_attribution->format('d/m/Y') }}
          </div>
        </div>
        @endif

        <form method="POST" action="{{ route('restitutions.store') }}">
          @csrf
          <div class="row g-3">
            <div class="col-12">
              <label class="form-label fw-medium">Attribution concernée <span class="text-danger">*</span></label>
              <select name="attribution_id"
                      class="form-select @error('attribution_id') is-invalid @enderror" required>
                <option value="">Sélectionner une attribution active...</option>
                @foreach($attributions_actives as $a)
                  <option value="{{ $a->id }}"
                    {{ old('attribution_id', $attribution?->id) == $a->id ? 'selected' : '' }}>
                    [{{ $a->numero_fiche }}] {{ $a->equipement->designation }}
                    — {{ $a->ressource->nom_complet }}
                    — {{ $a->site->nom }}
                  </option>
                @endforeach
              </select>
              @error('attribution_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
              <label class="form-label fw-medium">Date de restitution <span class="text-danger">*</span></label>
              <input type="date" name="date_restitution" class="form-control"
                     value="{{ old('date_restitution', date('Y-m-d')) }}" required>
            </div>
            <div class="col-md-4">
              <label class="form-label fw-medium">Reçu par <span class="text-danger">*</span></label>
              <input type="text" name="recu_par" class="form-control"
                     value="{{ old('recu_par', auth()->user()->name) }}" required>
            </div>
            <div class="col-md-4">
              <label class="form-label fw-medium">État de retour <span class="text-danger">*</span></label>
              <select name="etat_retour"
                      class="form-select @error('etat_retour') is-invalid @enderror" required>
                <option value="">Sélectionner...</option>
                <option value="bon"          {{ old('etat_retour') === 'bon'          ? 'selected' : '' }}>✅ Bon état</option>
                <option value="acceptable"   {{ old('etat_retour') === 'acceptable'   ? 'selected' : '' }}>🔵 Acceptable</option>
                <option value="endommagé"    {{ old('etat_retour') === 'endommagé'    ? 'selected' : '' }}>⚠️ Endommagé</option>
                <option value="hors_service" {{ old('etat_retour') === 'hors_service' ? 'selected' : '' }}>🔴 Hors service</option>
              </select>
              @error('etat_retour')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
              <label class="form-label fw-medium">Observations</label>
              <textarea name="observation" class="form-control" rows="3"
                        placeholder="État détaillé, pièces manquantes, dommages constatés...">{{ old('observation') }}</textarea>
            </div>
          </div>

          <div class="alert alert-warning d-flex align-items-center gap-2 mt-3" style="font-size:13px">
            <i class="bi bi-exclamation-triangle-fill"></i>
            L'équipement sera automatiquement remis en stock selon l'état de retour déclaré.
          </div>

          <div class="d-flex gap-2 justify-content-end">
            <a href="{{ route('restitutions.index') }}" class="btn btn-outline-secondary">Annuler</a>
            <button type="submit" class="btn btn-warning text-white">
              <i class="bi bi-check-lg me-1"></i>Valider la restitution
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection