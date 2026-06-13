@extends('layouts.app')
@section('title', 'Mouvements de stock')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
  <div>
    <h5 class="fw-bold mb-1">Mouvements de stock</h5>
    <small class="text-muted">{{ $stocks->total() }} mouvements enregistrés</small>
  </div>
  <a href="{{ route('stocks.create') }}" class="btn btn-primary btn-sm">
    <i class="bi bi-plus-lg me-1"></i>Nouveau mouvement
  </a>
</div>

<!-- Compteurs entrées / sorties -->
<div class="row g-3 mb-4">
  <div class="col-md-3">
    <div class="card text-center p-3" style="border-left:4px solid #16a34a">
      <div class="fw-bold text-success" style="font-size:24px">{{ $total_entrees }}</div>
      <small class="text-muted">Total entrées</small>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card text-center p-3" style="border-left:4px solid #dc2626">
      <div class="fw-bold text-danger" style="font-size:24px">{{ $total_sorties }}</div>
      <small class="text-muted">Total sorties</small>
    </div>
  </div>
</div>

<div class="filter-bar">
  <form method="GET" class="row g-2 align-items-end">
    <div class="col-md-3">
      <input type="text" name="search" class="form-control form-control-sm"
             placeholder="Désignation ou code..." value="{{ request('search') }}">
    </div>
    <div class="col-md-3">
      <select name="site_id" class="form-select form-select-sm">
        <option value="">Tous les sites</option>
        @foreach($sites as $s)
          <option value="{{ $s->id }}" {{ request('site_id') == $s->id ? 'selected' : '' }}>
            {{ $s->nom }}
          </option>
        @endforeach
      </select>
    </div>
    <div class="col-md-2">
      <select name="mouvement" class="form-select form-select-sm">
        <option value="">Tous les mouvements</option>
        <option value="entree" {{ request('mouvement') === 'entree' ? 'selected' : '' }}>↑ Entrée</option>
        <option value="sortie" {{ request('mouvement') === 'sortie' ? 'selected' : '' }}>↓ Sortie</option>
      </select>
    </div>
    <div class="col-md-2">
      <button type="submit" class="btn btn-sm btn-primary w-100">
        <i class="bi bi-search me-1"></i>Filtrer
      </button>
    </div>
    <div class="col-md-1">
      <a href="{{ route('stocks.index') }}" class="btn btn-sm btn-outline-secondary w-100">
        <i class="bi bi-x-lg"></i>
      </a>
    </div>
  </form>
</div>

<div class="card">
  <div class="card-body p-0">
    <table class="table table-hover mb-0">
      <thead>
        <tr>
          <th>Équipement</th>
          <th>Mouvement</th>
          <th>Qté</th>
          <th>Motif</th>
          <th>Site</th>
          <th>Effectué par</th>
          <th>Date</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($stocks as $s)
        <tr>
          <td>
            <span style="font-weight:500">{{ $s->equipement->designation }}</span>
            <br>
            <small class="text-muted">
              <code style="font-size:10px">{{ $s->equipement->code_inventaire }}</code>
            </small>
          </td>
          <td>
            <span class="badge bg-{{ $s->mouvement_badge }}">
              {{ $s->mouvement === 'entree' ? '↑ Entrée' : '↓ Sortie' }}
            </span>
          </td>
          <td>
            <span class="fw-bold">{{ $s->quantite }}</span>
          </td>
          <td style="font-size:13px">{{ $s->motif }}</td>
          <td>
            <span class="badge bg-light text-dark border" style="font-size:11px">
              {{ $s->site->nom }}
            </span>
          </td>
          <td style="font-size:13px">{{ $s->effectue_par }}</td>
          <td style="font-size:13px">{{ $s->date_mouvement->format('d/m/Y') }}</td>
          <td>
            <div class="d-flex gap-1">
              <a href="{{ route('stocks.show', $s) }}"
                 class="btn btn-sm btn-outline-primary btn-action">
                <i class="bi bi-eye"></i>
              </a>
              <a href="{{ route('stocks.edit', $s) }}"
                 class="btn btn-sm btn-outline-secondary btn-action">
                <i class="bi bi-pencil"></i>
              </a>
              <form method="POST" action="{{ route('stocks.destroy', $s) }}"
                    onsubmit="return confirm('Supprimer ce mouvement ?')">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-outline-danger btn-action">
                  <i class="bi bi-trash"></i>
                </button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="8">
            <div class="empty-state">
              <i class="bi bi-archive"></i>
              <p>Aucun mouvement de stock</p>
              <a href="{{ route('stocks.create') }}" class="btn btn-primary btn-sm">
                Enregistrer un mouvement
              </a>
            </div>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
<div class="mt-3">{{ $stocks->links() }}</div>
@endsection