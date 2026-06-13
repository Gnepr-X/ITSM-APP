@extends('layouts.app')
@section('title', 'Modifier le mouvement')
@section('content')

<div class="row justify-content-center">
  <div class="col-md-7">
    <div class="card">
      <div class="card-header fw-semibold">
        <i class="bi bi-pencil me-2"></i>Modifier le mouvement de stock
      </div>
      <div class="card-body">

        <div class="alert alert-light border mb-4" style="font-size:13.5px">
          <div class="row">
            <div class="col-md-6">
              <strong>Équipement :</strong>
              {{ $stock->equipement->designation }}<br>
              <small class="text-muted">{{ $stock->equipement->code_inventaire }}</small>
            </div>
            <div class="col-md-3">
              <strong>Type :</strong><br>
              <span class="badge bg-{{ $stock->mouvement_badge }}">
                {{ $stock->mouvement === 'entree' ? '↑ Entrée' : '↓ Sortie' }}
              </span>
            </div>
            <div class="col-md-3">
              <strong>Qté :</strong><br>
              <span class="fw-bold">{{ $stock->quantite }}</span>
            </div>
          </div>
        </div>

        <form method="POST" action="{{ route('stocks.update', $stock) }}">
          @csrf @method('PUT')
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label fw-medium">Motif <span class="text-danger">*</span></label>
              <input type="text" name="motif"
                     class="form-control @error('motif') is-invalid @enderror"
                     value="{{ old('motif', $stock->motif) }}"
                     placeholder="Ex: Achat, Transfert, Mise au rebut..." required>
              @error('motif')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
              <label class="form-label fw-medium">Effectué par <span class="text-danger">*</span></label>
              <input type="text" name="effectue_par" class="form-control"
                     value="{{ old('effectue_par', $stock->effectue_par) }}" required>
            </div>
            <div class="col-md-4">
              <label class="form-label fw-medium">Date du mouvement <span class="text-danger">*</span></label>
              <input type="date" name="date_mouvement" class="form-control"
                     value="{{ old('date_mouvement', $stock->date_mouvement->format('Y-m-d')) }}" required>
            </div>
            <div class="col-12">
              <label class="form-label fw-medium">Notes</label>
              <textarea name="notes" class="form-control" rows="3"
                        placeholder="Informations complémentaires...">{{ old('notes', $stock->notes) }}</textarea>
            </div>
          </div>
          <hr>
          <div class="d-flex gap-2 justify-content-end">
            <a href="{{ route('stocks.index') }}" class="btn btn-outline-secondary">Annuler</a>
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