@extends('layouts.app')
@section('title', 'Ressources humaines')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
  <div>
    <h5 class="fw-bold mb-1">Ressources humaines</h5>
    <small class="text-muted">{{ $ressources->total() }} ressource(s)</small>
  </div>
  <div class="d-flex gap-2">
    <a href="{{ route('pdf.ressources') }}" target="_blank" class="btn btn-outline-danger btn-sm">
      <i class="bi bi-file-pdf me-1"></i>PDF
    </a>
    <a href="{{ route('ressources.create') }}" class="btn btn-primary btn-sm">
      <i class="bi bi-plus-lg me-1"></i>Nouvelle ressource
    </a>
  </div>
</div>

<div class="filter-bar mb-3">
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
      <select name="departement" class="form-select form-select-sm">
        <option value="">Tous les départements</option>
        @foreach($departements as $departement)
          <option value="{{ $departement }}" {{ request('departement') === $departement ? 'selected' : '' }}>
            {{ $departement }}
          </option>
        @endforeach
      </select>
    </div>
    <div class="col-md-2">
      <button type="submit" class="btn btn-sm btn-primary w-100">
        <i class="bi bi-search me-1"></i>Filtrer
      </button>
    </div>
    <div class="col-md-1">
      <a href="{{ route('ressources.index') }}" class="btn btn-sm btn-outline-secondary w-100">
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
          <th>#</th>
          <th>Matricule</th>
          <th>Nom & prénom</th>
          <th>Poste</th>
          <th>Département</th>
          <th>Site</th>
          <th>Contact</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($ressources as $ressource)
        <tr>
          <td><span class="fw-semibold">{{ $loop->index + 1 }}</span></td>
          <td><code style="font-size:11px">{{ $ressource->matricule }}</code></td>
          <td>
            <span class="fw-semibold">{{ $ressource->nom_complet }}</span>
            <!-- <small class="text-muted">{{ $ressource->nom }} {{ $ressource->prenom }} </small> -->
          </td>
          <td>{{ $ressource->poste }}</td>
          <td>{{ $ressource->departement ?: '—' }}</td>
          <td>
            <span class="badge bg-light text-dark border" style="font-size:11px">
              <i class="bi bi-building me-1"></i>{{ $ressource->site->nom }}
            </span>
          </td>
          <td style="font-size:13px; white-space:nowrap;">
            @if($ressource->email)
              <div>{{ $ressource->email }}</div>
            @endif
            @if($ressource->telephone)
              <div>{{ $ressource->telephone }}</div>
            @endif
            @if(!$ressource->email && !$ressource->telephone)
              <span class="text-muted">—</span>
            @endif
          </td>
          <td>
            <div class="d-flex gap-1">
              <a href="{{ route('ressources.show', $ressource) }}" class="btn btn-sm btn-outline-primary btn-action">
                <i class="bi bi-eye"></i>
              </a>
              <a href="{{ route('ressources.edit', $ressource) }}" class="btn btn-sm btn-outline-secondary btn-action">
                <i class="bi bi-pencil"></i>
              </a>
              <form method="POST" action="{{ route('ressources.destroy', $ressource) }}" onsubmit="return confirm('Supprimer cette ressource ?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-sm btn-outline-danger btn-action">
                  <i class="bi bi-trash"></i>
                </button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="8">
            <div class="empty-state py-4 text-center">
              <i class="bi bi-people-fill"></i>
              <p>Aucune ressource trouvée</p>
            </div>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

<div class="mt-3">{{ $ressources->links() }}</div>
@endsection
