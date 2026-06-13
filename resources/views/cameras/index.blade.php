@extends('layouts.app')
@section('title', 'Caméras')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
  <div>
    <h5 class="fw-bold mb-1">Système de vidéosurveillance</h5>
    <small class="text-muted">{{ $cameras->total() }} caméras</small>
  </div>
  <a href="{{ route('cameras.create') }}" class="btn btn-primary btn-sm">
    <i class="bi bi-plus-lg me-1"></i>Nouvelle caméra
  </a>
</div>

<div class="filter-bar">
  <form method="GET" class="row g-2 align-items-end">
    <div class="col-md-3">
      <input type="text" name="search" class="form-control form-control-sm"
             placeholder="Référence ou emplacement..." value="{{ request('search') }}">
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
      <select name="statut" class="form-select form-select-sm">
        <option value="">Tous les statuts</option>
        <option value="actif"    {{ request('statut') === 'actif'    ? 'selected' : '' }}>Actif</option>
        <option value="inactif"  {{ request('statut') === 'inactif'  ? 'selected' : '' }}>Inactif</option>
        <option value="en_panne" {{ request('statut') === 'en_panne' ? 'selected' : '' }}>En panne</option>
      </select>
    </div>
    <div class="col-md-2">
      <button type="submit" class="btn btn-sm btn-primary w-100">
        <i class="bi bi-search me-1"></i>Filtrer
      </button>
    </div>
    <div class="col-md-1">
      <a href="{{ route('cameras.index') }}" class="btn btn-sm btn-outline-secondary w-100">
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
          <th>Référence</th>
          <th>Marque / Modèle</th>
          <th>Emplacement</th>
          <th>Site</th>
          <th>Adresse IP</th>
          <th>Installation</th>
          <th>Statut</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($cameras as $cam)
        <tr>
          <td><code style="font-size:11px">{{ $cam->reference }}</code></td>
          <td style="font-size:13px">{{ $cam->marque }} {{ $cam->modele }}</td>
          <td>
            <i class="bi bi-geo-alt me-1 text-muted"></i>{{ $cam->emplacement }}
          </td>
          <td>
            <span class="badge bg-light text-dark border" style="font-size:11px">
              {{ $cam->site->nom }}
            </span>
          </td>
          <td>
            @if($cam->adresse_ip)
              <code style="font-size:11px">{{ $cam->adresse_ip }}</code>
            @else
              <span class="text-muted">—</span>
            @endif
          </td>
          <td style="font-size:12px">
            {{ $cam->date_installation?->format('d/m/Y') ?? '—' }}
          </td>
          <td>
            <span class="badge bg-{{ $cam->statut_badge }}">
              {{ ucfirst(str_replace('_',' ', $cam->statut)) }}
            </span>
          </td>
          <td>
            <div class="d-flex gap-1">
              <a href="{{ route('cameras.edit', $cam) }}"
                 class="btn btn-sm btn-outline-secondary btn-action">
                <i class="bi bi-pencil"></i>
              </a>
              <form method="POST" action="{{ route('cameras.destroy', $cam) }}"
                    onsubmit="return confirm('Supprimer cette caméra ?')">
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
              <i class="bi bi-camera-video-off"></i>
              <p>Aucune caméra enregistrée</p>
              <a href="{{ route('cameras.create') }}" class="btn btn-primary btn-sm">
                Ajouter une caméra
              </a>
            </div>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
<div class="mt-3">{{ $cameras->links() }}</div>
@endsection