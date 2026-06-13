@extends('layouts.app')
@section('title', 'Nouveau mouvement de stock')
@section('content')

<div class="row justify-content-center">
  <div class="col-md-8">
    <div class="card">
      <div class="card-header fw-semibold">
        <i class="bi bi-archive me-2"></i>Nouveau mouvement de stock
      </div>
      <div class="card-body">
        <form method="POST" action="{{ route('stocks.store') }}">
          @csrf
          <div class="row g-3">

            <div class="col-md-6">
              <label class="form-label fw-medium">Type de mouvement <span class="text-danger">*</span></label>
              <div class="d-flex gap-3 mt-1">
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="mouvement"
                         id="entree" value="entree"
                         {{ old('mouvement','entree') === 'entree' ? 'checked' : '' }}>
                  <label class="form-check-label text-success fw-medium" for="entree">
                    <i class="bi bi-arrow-down-circle-fill me-1"></i>Entrée
                  </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="mouvement"
                         id="sortie" value="sortie"
                         {{ old('mouvement') === 'sortie' ? 'checked' : '' }}>
                  <label class="form-check-label text-danger fw-medium" for="sortie">
                    <i class="bi bi-arrow-up-circle-fill me-1"></i>Sortie
                  </label>
                </div>
              </div>
              @error('mouvement')<div class="text-danger" style="font-size:12px">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
              <label class="form-label fw-medium">Quantité <span class="text-danger">*</span></label>
              <input type="number" name="quantite"
                     class="form-control @error('quantite') is-invalid @enderror"
                     value="{{ old('quantite', 1) }}" min="1" required>
              @error('quantite')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-12">
              <label class="form-label fw-medium">Équipement <span class="text-danger">*</span></label>
              <select name="equipement_id"
                      class="form-select @error('equipement_id') is-invalid @enderror" required>
                <option value="">Sélectionner un équipement...</option>
                @foreach($equipements as $eq)
                  <option value="{{ $eq->id }}"
                    {{ old('equipement_id') == $eq->id ? 'selected' : '' }}>
                    [{{ $eq->code_inventaire }}] {{ $eq->designation }}
                    — {{ $eq->marque }} {{ $eq->modele }}
                    — {{ $eq->site->nom }}
                    ({{ ucfirst(str_replace('_',' ',$eq->statut)) }})
                  </option>
                @endforeach
              </select>
              @error('equipement_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

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
              <label class="form-label fw-medium">Date du mouvement <span class="text-danger">*</span></label>
              <input type="date" name="date_mouvement" class="form-control"
                     value="{{ old('date_mouvement', date('Y-m-d')) }}" required>
            </div>

            <div class="col-md-8">
              <label class="form-label fw-medium">Motif <span class="text-danger">*</span></label>
              <input type="text" name="motif"
                     class="form-control @error('motif') is-invalid @enderror"
                     value="{{ old('motif') }}"
                     placeholder="Ex: Achat neuf, Transfert inter-site, Mise au rebut..." required>
              @error('motif')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4">
              <label class="form-label fw-medium">Effectué par <span class="text-danger">*</span></label>
              <input type="text" name="effectue_par" class="form-control"
                     value="{{ old('effectue_par', auth()->user()->name) }}" required>
            </div>

            <div class="col-12">
              <label class="form-label fw-medium">Notes</label>
              <textarea name="notes" class="form-control" rows="2"
                        placeholder="Informations complémentaires, numéro de bon de livraison...">{{ old('notes') }}</textarea>
            </div>
          </div>

          <hr>

          <div class="alert alert-info d-flex align-items-center gap-2" style="font-size:13px">
            <i class="bi bi-info-circle-fill"></i>
            Le statut de l'équipement sera mis à jour automatiquement :
            <strong>Entrée → Disponible</strong> · <strong>Sortie → Hors service</strong>
          </div>

          <div class="d-flex gap-2 justify-content-end">
            <a href="{{ route('stocks.index') }}" class="btn btn-outline-secondary">Annuler</a>
            <button type="submit" class="btn btn-primary">
              <i class="bi bi-check-lg me-1"></i>Enregistrer le mouvement
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection