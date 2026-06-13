@extends('layouts.app')
@section('title', 'Tableau de bord')
@section('content')

<!-- Stat cards -->
<div class="row g-3 mb-4">
  <div class="col-md-3">
    <div class="stat-card" style="background:linear-gradient(135deg,#667eea,#764ba2)">
      <div class="d-flex justify-content-between align-items-start">
        <div>
          <div class="stat-value">{{ $total_equipements }}</div>
          <div class="stat-label">Total équipements</div>
        </div>
        <i class="bi bi-laptop stat-icon"></i>
      </div>
      <div class="mt-2" style="font-size:12px;opacity:.8">
        {{ $disponibles }} disponibles · {{ $attribues }} attribués
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="stat-card" style="background:linear-gradient(135deg,#f093fb,#f5576c)">
      <div class="d-flex justify-content-between align-items-start">
        <div>
          <div class="stat-value">{{ $interventions_ouvertes }}</div>
          <div class="stat-label">Interventions ouvertes</div>
        </div>
        <i class="bi bi-tools stat-icon"></i>
      </div>
      <div class="mt-2" style="font-size:12px;opacity:.8">
        {{ $interventions_critiques }} critiques · {{ $interventions_resolues }} résolues ce mois
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="stat-card" style="background:linear-gradient(135deg,#4facfe,#00f2fe)">
      <div class="d-flex justify-content-between align-items-start">
        <div>
          <div class="stat-value">{{ $total_sites }}</div>
          <div class="stat-label">Sites gérés</div>
        </div>
        <i class="bi bi-building stat-icon"></i>
      </div>
      <div class="mt-2" style="font-size:12px;opacity:.8">
        {{ $total_ressources }} ressources · {{ $acces_wifis_actifs }} BOX actives
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="stat-card" style="background:linear-gradient(135deg,#43e97b,#38f9d7)">
      <div class="d-flex justify-content-between align-items-start">
        <div>
          <div class="stat-value">{{ $cameras_actives }}</div>
          <div class="stat-label">Caméras actives</div>
        </div>
        <i class="bi bi-camera-video stat-icon"></i>
      </div>
      <div class="mt-2" style="font-size:12px;opacity:.8">
        {{ $alarmes_actives }} alarmes actives
      </div>
    </div>
  </div>
</div>

<div class="row g-3 mb-4">
  <!-- Équipements par statut -->
  <div class="col-md-4">
    <div class="card h-100">
      <div class="card-header d-flex justify-content-between align-items-center">
        <span class="fw-semibold">État des équipements</span>
        <a href="{{ route('equipements.index') }}" class="btn btn-sm btn-outline-primary btn-action">Voir tout</a>
      </div>
      <div class="card-body">
        @php
          $total = max($total_equipements, 1);
        @endphp
        <div class="mb-3">
          <div class="d-flex justify-content-between mb-1">
            <small>Disponibles</small><small class="text-success fw-bold">{{ $disponibles }}</small>
          </div>
          <div class="progress" style="height:6px">
            <div class="progress-bar bg-success" style="width:{{ ($disponibles/$total)*100 }}%"></div>
          </div>
        </div>
        <div class="mb-3">
          <div class="d-flex justify-content-between mb-1">
            <small>Attribués</small><small class="text-primary fw-bold">{{ $attribues }}</small>
          </div>
          <div class="progress" style="height:6px">
            <div class="progress-bar bg-primary" style="width:{{ ($attribues/$total)*100 }}%"></div>
          </div>
        </div>
        <div class="mb-3">
          <div class="d-flex justify-content-between mb-1">
            <small>En réparation</small><small class="text-warning fw-bold">{{ $en_reparation }}</small>
          </div>
          <div class="progress" style="height:6px">
            <div class="progress-bar bg-warning" style="width:{{ ($en_reparation/$total)*100 }}%"></div>
          </div>
        </div>
        <div>
          <div class="d-flex justify-content-between mb-1">
            <small>Hors service</small><small class="text-danger fw-bold">{{ $hors_service }}</small>
          </div>
          <div class="progress" style="height:6px">
            <div class="progress-bar bg-danger" style="width:{{ ($hors_service/$total)*100 }}%"></div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Interventions urgentes -->
  <div class="col-md-8">
    <div class="card h-100">
      <div class="card-header d-flex justify-content-between align-items-center">
        <span class="fw-semibold">Interventions en cours</span>
        <a href="{{ route('interventions.index') }}" class="btn btn-sm btn-outline-primary btn-action">Voir tout</a>
      </div>
      <div class="card-body p-0">
        @forelse($dernieres_interventions as $i)
        <div class="d-flex align-items-center gap-3 px-4 py-3 border-bottom">
          <span class="badge bg-{{ $i->priorite_badge }} rounded-pill">{{ ucfirst($i->priorite) }}</span>
          <div class="flex-grow-1">
            <div style="font-size:13.5px;font-weight:500">{{ $i->titre }}</div>
            <small class="text-muted">{{ $i->site->nom }} · {{ $i->ressource?->nom_complet ?? 'Non assigné' }}</small>
          </div>
          <span class="badge bg-{{ $i->statut_badge }}">{{ ucfirst($i->statut) }}</span>
          <a href="{{ route('interventions.show', $i) }}" class="btn btn-sm btn-outline-secondary btn-action">
            <i class="bi bi-eye"></i>
          </a>
        </div>
        @empty
        <div class="empty-state"><i class="bi bi-check-circle text-success"></i>Aucune intervention en cours</div>
        @endforelse
      </div>
    </div>
  </div>
</div>

<div class="row g-3">
  <!-- Équipements par site -->
  <div class="col-md-6">
    <div class="card">
      <div class="card-header fw-semibold">Équipements par site</div>
      <div class="card-body p-0">
        <table class="table table-hover mb-0">
          <thead><tr><th>Site</th><th>Équipements</th><th></th></tr></thead>
          <tbody>
            @foreach($equipements_par_site as $site)
            <tr>
              <td><i class="bi bi-building me-2 text-muted"></i>{{ $site->nom }}</td>
              <td><span class="badge bg-light text-dark border">{{ $site->equipements_count }}</span></td>
              <td>
                <a href="{{ route('sites.show', $site) }}" class="btn btn-sm btn-outline-primary btn-action">
                  <i class="bi bi-eye"></i>
                </a>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Derniers mouvements stock -->
  <div class="col-md-6">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <span class="fw-semibold">Derniers mouvements de stock</span>
        <a href="{{ route('stocks.index') }}" class="btn btn-sm btn-outline-primary btn-action">Voir tout</a>
      </div>
      <div class="card-body p-0">
        <table class="table table-hover mb-0">
          <thead><tr><th>Équipement</th><th>Mvt</th><th>Site</th><th>Date</th></tr></thead>
          <tbody>
            @forelse($derniers_mouvements as $s)
            <tr>
              <td style="font-size:13px">{{ Str::limit($s->equipement->designation, 25) }}</td>
              <td>
                <span class="badge bg-{{ $s->mouvement_badge }}">
                  {{ $s->mouvement === 'entree' ? '↑ Entrée' : '↓ Sortie' }}
                </span>
              </td>
              <td style="font-size:12px" class="text-muted">{{ $s->site->nom }}</td>
              <td style="font-size:12px">{{ $s->date_mouvement->format('d/m/Y') }}</td>
            </tr>
            @empty
            <tr><td colspan="4" class="text-center text-muted py-3">Aucun mouvement</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

@endsection