@extends('layouts.app')
@section('title', 'Accès WIFI')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
  <div>
    <h5 class="fw-bold mb-1">Gestion des accès WIFI</h5>
    <small class="text-muted">{{ $accesWifis->total() }} BOX enregistrées</small>
  </div>
  <a href="{{ route('acces-wifis.create') }}" class="btn btn-primary btn-sm">
    <i class="bi bi-plus-lg me-1"></i>Nouvelle BOX
  </a>
</div>

<div class="filter-bar">
  <form method="GET" class="row g-2 align-items-end">
    <div class="col-md-3">
      <select name="site_id" class="form-select form-select-sm">
        <option value="">Tous les sites</option>
        @foreach($sites as $s)
          <option value="{{ $s->id }}" {{ request('site_id') == $s->id ? 'selected' : '' }}>{{ $s->nom }}</option>
        @endforeach
      </select>
    </div>
    <div class="col-md-2">
      <select name="operateur" class="form-select form-select-sm">
        <option value="">Tous opérateurs</option>
        <option value="ORANGE" {{ request('operateur') === 'ORANGE' ? 'selected' : '' }}>ORANGE</option>
        <option value="MTN" {{ request('operateur') === 'MTN' ? 'selected' : '' }}>MTN</option>
        <option value="AUTRE" {{ request('operateur') === 'AUTRE' ? 'selected' : '' }}>AUTRE</option>
      </select>
    </div>
    <div class="col-md-2">
      <select name="statut" class="form-select form-select-sm">
        <option value="">Tous les statuts</option>
        <option value="actif" {{ request('statut') === 'actif' ? 'selected' : '' }}>Actif</option>
        <option value="inactif" {{ request('statut') === 'inactif' ? 'selected' : '' }}>Inactif</option>
      </select>
    </div>
    <div class="col-md-2">
      <button type="submit" class="btn btn-sm btn-primary w-100"><i class="bi bi-search me-1"></i>Filtrer</button>
    </div>
    <div class="col-md-1">
      <a href="{{ route('acces-wifis.index') }}" class="btn btn-sm btn-outline-secondary w-100"><i class="bi bi-x-lg"></i></a>
    </div>
    <div class="col-md-2 text-end">
      <a href="{{ route('pdf.acces_wifi', request('site_id', $sites->first()?->id)) }}" target="_blank"
         class="btn btn-sm btn-outline-danger">
        <i class="bi bi-file-pdf me-1"></i>PDF par site
      </a>
    </div>
  </form>
</div>

<div class="row g-3">
  @forelse($accesWifis as $wifi)
  <div class="col-md-4">
    <div class="card h-100">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start mb-2">
          <h6 class="fw-bold mb-0">{{ $wifi->nom_box }}</h6>
          <div class="d-flex gap-1">
            <span class="badge {{ $wifi->operateur === 'ORANGE' ? 'bg-warning text-dark' : 'bg-warning text-dark' }}">
              {{ $wifi->operateur }}
            </span>
            <span class="badge bg-{{ $wifi->statut === 'actif' ? 'success' : 'secondary' }}">
              {{ ucfirst($wifi->statut) }}
            </span>
          </div>
        </div>
        <div style="font-size:13px">
          <div class="mb-1"><i class="bi bi-building me-2 text-muted"></i>{{ $wifi->site->nom }}</div>
          <div class="mb-1"><i class="bi bi-wifi me-2 text-muted"></i>SSID: <strong>{{ $wifi->ssid }}</strong></div>
          <div class="mb-1">
            <i class="bi bi-key me-2 text-muted"></i>
            <span id="pwd-{{ $wifi->id }}" style="filter:blur(4px);cursor:pointer"
                  onclick="this.style.filter='none'" title="Cliquer pour afficher">
              {{ $wifi->mot_de_passe }}
            </span>
          </div>
          @if($wifi->adresse_ip)
            <div><i class="bi bi-router me-2 text-muted"></i>IP: <code style="font-size:11px">{{ $wifi->adresse_ip }}</code></div>
          @endif
        </div>
        <div class="d-flex gap-2 mt-3">
          <a href="{{ route('acces-wifis.edit', $wifi) }}" class="btn btn-sm btn-outline-secondary flex-grow-1">
            <i class="bi bi-pencil me-1"></i>Modifier
          </a>
          <a href="{{ route('pdf.acces_wifi', $wifi->site) }}" target="_blank"
             class="btn btn-sm btn-outline-danger">
            <i class="bi bi-file-pdf"></i>
          </a>
          <form method="POST" action="{{ route('acces-wifis.destroy', $wifi) }}"
                onsubmit="return confirm('Supprimer cet accès ?')">
            @csrf @method('DELETE')
            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
          </form>
        </div>
      </div>
    </div>
  </div>
  @empty
  <div class="col-12">
    <div class="empty-state">
      <i class="bi bi-wifi-off"></i>
      <p>Aucun accès WIFI enregistré</p>
      <a href="{{ route('acces-wifis.create') }}" class="btn btn-primary">Ajouter une BOX</a>
    </div>
  </div>
  @endforelse
</div>
<div class="mt-3">{{ $accesWifis->links() }}</div>
@endsection