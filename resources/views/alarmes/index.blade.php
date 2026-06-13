@extends('layouts.app')
@section('title', 'Alarmes')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
  <div>
    <h5 class="fw-bold mb-1">Systèmes d'alarme</h5>
    <small class="text-muted">{{ $alarmes->total() }} systèmes</small>
  </div>
  <a href="{{ route('alarmes.create') }}" class="btn btn-primary btn-sm">
    <i class="bi bi-plus-lg me-1"></i>Nouveau système
  </a>
</div>

<div class="filter-bar">
  <form method="GET" class="row g-2 align-items-end">
    <div class="col-md-4">
      <select name="site_id" class="form-select form-select-sm">
        <option value="">Tous les sites</option>
        @foreach($sites as $s)
          <option value="{{ $s->id }}" {{ request('site_id') == $s->id ? 'selected' : '' }}>
            {{ $s->nom }}
          </option>
        @endforeach
      </select>
    </div>
    <div class="col-md-3">
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
      <a href="{{ route('alarmes.index') }}" class="btn btn-sm btn-outline-secondary w-100">
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
          <th>Type de système</th>
          <th>Marque</th>
          <th>Zone couverte</th>
          <th>Site</th>
          <th>Code accès</th>
          <th>Installation</th>
          <th>Statut</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($alarmes as $alarme)
        <tr>
          <td style="font-weight:500">{{ $alarme->type_systeme }}</td>
          <td style="font-size:13px">{{ $alarme->marque ?? '—' }}</td>
          <td>
            <i class="bi bi-shield me-1 text-muted"></i>{{ $alarme->zone_couverte }}
          </td>
          <td>
            <span class="badge bg-light text-dark border" style="font-size:11px">
              {{ $alarme->site->nom }}
            </span>
          </td>
          <td>
            @if($alarme->code_acces && auth()->user()->hasRole('admin'))
              <span id="code-{{ $alarme->id }}"
                    style="filter:blur(4px);cursor:pointer;font-family:monospace;font-size:12px"
                    onclick="this.style.filter='none'"
                    title="Cliquer pour afficher">
                {{ $alarme->code_acces }}
              </span>
            @elseif($alarme->code_acces)
              <span class="text-muted" style="font-size:12px">
                <i class="bi bi-lock me-1"></i>Admin uniquement
              </span>
            @else
              <span class="text-muted">—</span>
            @endif
          </td>
          <td style="font-size:12px">
            {{ $alarme->date_installation?->format('d/m/Y') ?? '—' }}
          </td>
          <td>
            <span class="badge bg-{{ $alarme->statut_badge }}">
              {{ ucfirst(str_replace('_',' ',$alarme->statut)) }}
            </span>
          </td>
          <td>
            <div class="d-flex gap-1">
              <a href="{{ route('alarmes.edit', $alarme) }}"
                 class="btn btn-sm btn-outline-secondary btn-action">
                <i class="bi bi-pencil"></i>
              </a>
              <form method="POST" action="{{ route('alarmes.destroy', $alarme) }}"
                    onsubmit="return confirm('Supprimer ce système ?')">
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
              <i class="bi bi-shield-x"></i>
              <p>Aucun système d'alarme enregistré</p>
              <a href="{{ route('alarmes.create') }}" class="btn btn-primary btn-sm">
                Ajouter un système
              </a>
            </div>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
<div class="mt-3">{{ $alarmes->links() }}</div>
@endsection