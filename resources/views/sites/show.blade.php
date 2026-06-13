@extends('layouts.app')
@section('title', $site->nom)
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h5 class="fw-bold mb-1"><i class="bi bi-building me-2"></i>{{ $site->nom }}</h5>
    <small class="text-muted">{{ $site->ville }}{{ $site->adresse ? ' · '.$site->adresse : '' }}</small>
  </div>
  <div class="d-flex gap-2">
    <a href="{{ route('pdf.acces_wifi', $site) }}" target="_blank" class="btn btn-outline-danger btn-sm">
      <i class="bi bi-file-pdf me-1"></i>WIFI PDF
    </a>
    <a href="{{ route('pdf.inventaire') }}?site_id={{ $site->id }}" target="_blank" class="btn btn-outline-danger btn-sm">
      <i class="bi bi-file-pdf me-1"></i>Inventaire PDF
    </a>
    <a href="{{ route('sites.edit', $site) }}" class="btn btn-outline-secondary btn-sm">
      <i class="bi bi-pencil me-1"></i>Modifier
    </a>
  </div>
</div>

<!-- Stats du site -->
<div class="row g-3 mb-4">
  <div class="col-md-3">
    <div class="card text-center p-3">
      <div class="fw-bold text-primary" style="font-size:24px">{{ $stats['equipements_disponibles'] }}</div>
      <small class="text-muted">Équipements disponibles</small>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card text-center p-3">
      <div class="fw-bold text-success" style="font-size:24px">{{ $stats['equipements_attribues'] }}</div>
      <small class="text-muted">Équipements attribués</small>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card text-center p-3">
      <div class="fw-bold text-warning" style="font-size:24px">{{ $stats['equipements_en_panne'] }}</div>
      <small class="text-muted">En panne / réparation</small>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card text-center p-3">
      <div class="fw-bold text-danger" style="font-size:24px">{{ $stats['interventions_ouvertes'] }}</div>
      <small class="text-muted">Tickets ouverts</small>
    </div>
  </div>
</div>

<div class="row g-3">
  <!-- Équipements -->
  <div class="col-md-8">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <span class="fw-semibold">Équipements</span>
        <a href="{{ route('equipements.index') }}?site_id={{ $site->id }}" class="btn btn-sm btn-outline-primary btn-action">Voir tout</a>
      </div>
      <div class="card-body p-0">
        <table class="table table-hover mb-0">
          <thead><tr><th>Code</th><th>Désignation</th><th>Type</th><th>Statut</th></tr></thead>
          <tbody>
            @forelse($site->equipements->take(8) as $eq)
            <tr>
              <td><code style="font-size:11px">{{ $eq->code_inventaire }}</code></td>
              <td>{{ $eq->designation }}</td>
              <td><span class="badge bg-light text-dark border">{{ $eq->type }}</span></td>
              <td>
                @php
                  $colors = ['disponible'=>'success','attribue'=>'primary','en_reparation'=>'warning','hors_service'=>'danger'];
                @endphp
                <span class="badge bg-{{ $colors[$eq->statut] ?? 'secondary' }}">{{ ucfirst($eq->statut) }}</span>
              </td>
            </tr>
            @empty
            <tr><td colspan="4" class="text-center text-muted py-3">Aucun équipement</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Infos + WIFI -->
  <div class="col-md-4">
    <div class="card mb-3">
      <div class="card-header fw-semibold">Informations</div>
      <div class="card-body">
        <dl class="row mb-0" style="font-size:13.5px">
          <dt class="col-5 text-muted">Responsable</dt>
          <dd class="col-7">{{ $site->responsable ?? '—' }}</dd>
          <dt class="col-5 text-muted">Téléphone</dt>
          <dd class="col-7">{{ $site->telephone ?? '—' }}</dd>
          <dt class="col-5 text-muted">Adresse</dt>
          <dd class="col-7">{{ $site->adresse ?? '—' }}</dd>
        </dl>
      </div>
    </div>
    <div class="card">
      <div class="card-header fw-semibold">Accès WIFI</div>
      <div class="card-body p-0">
        @forelse($site->accesWifis as $wifi)
        <div class="px-3 py-2 border-bottom">
          <div class="d-flex justify-content-between">
            <span style="font-size:13px;font-weight:500">{{ $wifi->nom_box }}</span>
            <span class="badge {{ $wifi->operateur === 'ORANGE' ? 'bg-warning text-dark' : 'bg-warning text-dark' }}">
              {{ $wifi->operateur }}
            </span>
          </div>
          <small class="text-muted">SSID: {{ $wifi->ssid }}</small>
        </div>
        @empty
        <div class="text-center text-muted py-3" style="font-size:13px">Aucune BOX WIFI</div>
        @endforelse
      </div>
    </div>
  </div>
</div>
@endsection