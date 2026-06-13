@extends('layouts.app')
@section('title', 'Sites & Localités')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h5 class="mb-1 fw-bold">Sites & Localités</h5>
    <small class="text-muted">{{ $sites->total() }} sites gérés</small>
  </div>
  <a href="{{ route('sites.create') }}" class="btn btn-primary">
    <i class="bi bi-plus-lg me-1"></i> Nouveau site
  </a>
</div>

<div class="row g-3">
  @forelse($sites as $site)
  <div class="col-md-4">
    <div class="card h-100">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start mb-3">
          <div>
            <h6 class="fw-bold mb-1">{{ $site->nom }}</h6>
            <small class="text-muted"><i class="bi bi-geo-alt me-1"></i>{{ $site->ville }}</small>
          </div>
          @if($site->interventions_count > 0)
            <span class="badge bg-danger">{{ $site->interventions_count }} ticket(s)</span>
          @else
            <span class="badge bg-success">OK</span>
          @endif
        </div>
        <div class="row g-2 mb-3">
          <div class="col-6">
            <div class="bg-light rounded p-2 text-center">
              <div class="fw-bold text-primary">{{ $site->equipements_count }}</div>
              <small class="text-muted">Équipements</small>
            </div>
          </div>
          <div class="col-6">
            <div class="bg-light rounded p-2 text-center">
              <div class="fw-bold text-info">{{ $site->ressources_count }}</div>
              <small class="text-muted">Ressources</small>
            </div>
          </div>
          <div class="col-6">
            <div class="bg-light rounded p-2 text-center">
              <div class="fw-bold text-success">{{ $site->cameras_count }}</div>
              <small class="text-muted">Caméras</small>
            </div>
          </div>
          <div class="col-6">
            <div class="bg-light rounded p-2 text-center">
              <div class="fw-bold text-warning">{{ $site->acces_wifis_count }}</div>
              <small class="text-muted">BOX WIFI</small>
            </div>
          </div>
        </div>
        @if($site->responsable)
          <small class="text-muted d-block mb-2">
            <i class="bi bi-person me-1"></i>{{ $site->responsable }}
          </small>
        @endif
        <div class="d-flex gap-2">
          <a href="{{ route('sites.show', $site) }}" class="btn btn-sm btn-outline-primary flex-grow-1">
            <i class="bi bi-eye me-1"></i>Détails
          </a>
          <a href="{{ route('sites.edit', $site) }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-pencil"></i>
          </a>
          <form method="POST" action="{{ route('sites.destroy', $site) }}" onsubmit="return confirm('Supprimer ce site ?')">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
          </form>
        </div>
      </div>
    </div>
  </div>
  @empty
  <div class="col-12">
    <div class="empty-state">
      <i class="bi bi-building"></i>
      <p>Aucun site enregistré</p>
      <a href="{{ route('sites.create') }}" class="btn btn-primary">Ajouter le premier site</a>
    </div>
  </div>
  @endforelse
</div>

<div class="mt-4">{{ $sites->links() }}</div>
@endsection