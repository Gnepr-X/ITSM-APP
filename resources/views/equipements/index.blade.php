@extends('layouts.app')
@section('title', 'Inventaire des équipements')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
  <div>
    <h5 class="fw-bold mb-1">Inventaire</h5>
    <small class="text-muted">{{ $equipements->total() }} équipements</small>
  </div>
  <div class="d-flex gap-2">
    <a href="{{ route('pdf.inventaire') }}" target="_blank" class="btn btn-outline-danger btn-sm">
      <i class="bi bi-file-pdf me-1"></i>PDF
    </a>
    <a href="{{ route('equipements.create') }}" class="btn btn-primary btn-sm">
      <i class="bi bi-plus-lg me-1"></i>Nouvel équipement
    </a>
  </div>
</div>

<!-- Filtres -->
<div class="filter-bar">
  <form method="GET" class="row g-2 align-items-end">
    <div class="col-md-3">
      <input type="text" name="search" class="form-control form-control-sm"
             placeholder="Recherche..." value="{{ request('search') }}">
    </div>
    <div class="col-md-2">
      <select name="site_id" class="form-select form-select-sm">
        <option value="">Tous les sites</option>
        @foreach($sites as $site)
          <option value="{{ $site->id }}" {{ request('site_id') == $site->id ? 'selected' : '' }}>
            {{ $site->nom }}
          </option>
        @endforeach
      </select>
    </div>
    <div class="col-md-2">
      <select name="type" class="form-select form-select-sm">
        <option value="">Tous les types</option>
        @foreach(['ordinateur','imprimante','serveur','switch','routeur','camera','alarme','autre'] as $t)
          <option value="{{ $t }}" {{ request('type') === $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
        @endforeach
      </select>
    </div>
    <div class="col-md-2">
      <select name="statut" class="form-select form-select-sm">
        <option value="">Tous les statuts</option>
        <option value="disponible" {{ request('statut') === 'disponible' ? 'selected' : '' }}>Disponible</option>
        <option value="attribue" {{ request('statut') === 'attribue' ? 'selected' : '' }}>Attribué</option>
        <option value="en_reparation" {{ request('statut') === 'en_reparation' ? 'selected' : '' }}>En réparation</option>
        <option value="hors_service" {{ request('statut') === 'hors_service' ? 'selected' : '' }}>Hors service</option>
      </select>
    </div>
    <div class="col-md-2">
      <button type="submit" class="btn btn-sm btn-primary w-100">
        <i class="bi bi-search me-1"></i>Filtrer
      </button>
    </div>
    <div class="col-md-1">
      <a href="{{ route('equipements.index') }}" class="btn btn-sm btn-outline-secondary w-100">
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
          <th>Code inventaire</th>
          <th>Désignation</th>
          <th>Type</th>
          <th>Marque / Modèle</th>
          <th>Site</th>
          <th>Statut</th>
          <th>QR</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($equipements as $eq)
        <tr>
          <td><code style="font-size:11px">{{ $eq->code_inventaire }}</code></td>
          <td>
            <span style="font-weight:500">{{ $eq->designation }}</span>
            @if($eq->numero_serie)
              <br><small class="text-muted">S/N: {{ $eq->numero_serie }}</small>
            @endif
          </td>
          <td><span class="badge bg-light text-dark border">{{ ucfirst($eq->type) }}</span></td>
          <td style="font-size:13px">{{ $eq->marque }} {{ $eq->modele }}</td>
          <td>
            <span class="badge bg-light text-dark border" style="font-size:11px">
              <i class="bi bi-building me-1"></i>{{ $eq->site->nom }}
            </span>
          </td>
          <td>
            @php
              $colors = ['disponible'=>'success','attribue'=>'primary','en_reparation'=>'warning','hors_service'=>'danger'];
            @endphp
            <span class="badge bg-{{ $colors[$eq->statut] ?? 'secondary' }}">{{ ucfirst(str_replace('_',' ',$eq->statut)) }}</span>
          </td>
          <td>
            @if($eq->qr_code)
              <a href="{{ route('equipements.qrcode', $eq) }}" target="_blank" title="Voir QR">
                <i class="bi bi-qr-code" style="font-size:18px;color:#6366f1"></i>
              </a>
            @else
              <span class="text-muted">—</span>
            @endif
          </td>
          <td>
            <div class="d-flex gap-1">
              <a href="{{ route('equipements.show', $eq) }}" class="btn btn-sm btn-outline-primary btn-action">
                <i class="bi bi-eye"></i>
              </a>
              <a href="{{ route('equipements.edit', $eq) }}" class="btn btn-sm btn-outline-secondary btn-action">
                <i class="bi bi-pencil"></i>
              </a>
              <form method="POST" action="{{ route('equipements.destroy', $eq) }}"
                    onsubmit="return confirm('Supprimer cet équipement ?')">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-outline-danger btn-action"><i class="bi bi-trash"></i></button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="8">
            <div class="empty-state">
              <i class="bi bi-laptop"></i>
              <p>Aucun équipement trouvé</p>
            </div>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
<div class="mt-3">{{ $equipements->links() }}</div>
@endsection