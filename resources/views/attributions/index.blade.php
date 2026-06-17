@extends('layouts.app')
@section('title', 'Attributions')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
  <div>
    <h5 class="fw-bold mb-1">Attributions</h5>
    <small class="text-muted">{{ $attributions->total() }} enregistrements</small>
  </div>
  <a href="{{ route('attributions.create') }}" class="btn btn-primary btn-sm">
    <i class="bi bi-plus-lg me-1"></i>Nouvelle attribution
  </a>
</div>

<div class="filter-bar">
  <form method="GET" class="row g-2 align-items-end">
    <div class="col-md-3">
      <input type="text" name="search" class="form-control form-control-sm"
             placeholder="Nom ou matricule..." value="{{ request('search') }}">
    </div>
    <div class="col-md-3">
      <select name="site_id" class="form-select form-select-sm">
        <option value="">Tous les sites</option>
        @foreach($sites as $s)
          <option value="{{ $s->id }}" {{ request('site_id') == $s->id ? 'selected' : '' }}>{{ $s->nom }}</option>
        @endforeach
      </select>
    </div>
    <div class="col-md-2">
      <select name="statut" class="form-select form-select-sm">
        <option value="">Tous les statuts</option>
        <option value="actif" {{ request('statut') === 'actif' ? 'selected' : '' }}>Actif</option>
        <option value="restitue" {{ request('statut') === 'restitue' ? 'selected' : '' }}>Restitué</option>
      </select>
    </div>
    <div class="col-md-2">
      <button type="submit" class="btn btn-sm btn-primary w-100"><i class="bi bi-search me-1"></i>Filtrer</button>
    </div>
    <div class="col-md-1">
      <a href="{{ route('attributions.index') }}" class="btn btn-sm btn-outline-secondary w-100"><i class="bi bi-x-lg"></i></a>
    </div>
  </form>
</div>

<div class="card">
  <div class="card-body p-0">
    <table class="table table-hover mb-0">
      <thead>
        <tr>
          <th>N° Fiche</th>
          <th>Bénéficiaire</th>
          <th>Équipement</th>
          <th>Site</th>
          <th>Date</th>
          <th>Statut</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($attributions as $a)
        <tr>
          <td><code style="font-size:11px">{{ $a->numero_fiche }}</code></td>
          <td>
            <span style="font-weight:500">{{ $a->ressource->nom_complet }}</span>
            <br><small class="text-muted">{{ $a->ressource->matricule }}</small>
          </td>
          <td>
            {{ $a->equipement->designation }}
            <br><small class="text-muted"><code style="font-size:10px">{{ $a->equipement->code_inventaire }}</code></small>
          </td>
          <td><span class="badge bg-light text-dark border" style="font-size:11px">{{ $a->site->nom }}</span></td>
          <td style="font-size:13px">{{ $a->date_attribution->format('d/m/Y') }}</td>
          <td>
            <span class="badge bg-{{ $a->statut === 'actif' ? 'success' : 'danger' }}">
              {{ ucfirst($a->statut) }}
            </span>
          </td>
          <td>
            <div class="d-flex gap-1">
              <a href="{{ route('pdf.attribution', $a) }}" target="_blank"
                 class="btn btn-sm btn-outline-danger btn-action" title="Fiche PDF">
                <i class="bi bi-file-pdf"></i>
              </a>
              <a href="{{ route('attributions.show', $a) }}"
                 class="btn btn-sm btn-outline-primary btn-action"><i class="bi bi-eye"></i></a>
              @if($a->statut === 'actif')
                <a href="{{ route('restitutions.create') }}?attribution_id={{ $a->id }}"
                   class="btn btn-sm btn-outline-warning btn-action" title="Restituer">
                  <i class="bi bi-box-arrow-left"></i>
                </a>
              @endif
            </div>
          </td>
        </tr>
        @empty
        <tr><td colspan="7">
          <div class="empty-state"><i class="bi bi-box-arrow-in-right"></i><p>Aucune attribution</p></div>
        </td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
<div class="mt-3">{{ $attributions->links() }}</div>
@endsection