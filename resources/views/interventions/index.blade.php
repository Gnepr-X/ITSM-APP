@extends('layouts.app')
@section('title', 'Interventions')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
  <div>
    <h5 class="fw-bold mb-1">Interventions & Support</h5>
    <small class="text-muted">{{ $interventions->total() }} tickets</small>
  </div>
  <div class="d-flex gap-2">
    <a href="{{ route('pdf.interventions') }}" target="_blank" class="btn btn-outline-danger btn-sm">
      <i class="bi bi-file-pdf me-1"></i>PDF
    </a>
    <a href="{{ route('interventions.create') }}" class="btn btn-primary btn-sm">
      <i class="bi bi-plus-lg me-1"></i>Nouveau ticket
    </a>
  </div>
</div>

<div class="filter-bar">
  <form method="GET" class="row g-2 align-items-end">
    <div class="col-md-3">
      <input type="text" name="search" class="form-control form-control-sm"
             placeholder="Titre ou numéro ticket..." value="{{ request('search') }}">
    </div>
    <div class="col-md-2">
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
        <option value="ouvert" {{ request('statut') === 'ouvert' ? 'selected' : '' }}>Ouvert</option>
        <option value="en_cours" {{ request('statut') === 'en_cours' ? 'selected' : '' }}>En cours</option>
        <option value="resolu" {{ request('statut') === 'resolu' ? 'selected' : '' }}>Résolu</option>
        <option value="ferme" {{ request('statut') === 'ferme' ? 'selected' : '' }}>Fermé</option>
      </select>
    </div>
    <div class="col-md-2">
      <select name="priorite" class="form-select form-select-sm">
        <option value="">Toutes priorités</option>
        <option value="critique" {{ request('priorite') === 'critique' ? 'selected' : '' }}>Critique</option>
        <option value="haute" {{ request('priorite') === 'haute' ? 'selected' : '' }}>Haute</option>
        <option value="normale" {{ request('priorite') === 'normale' ? 'selected' : '' }}>Normale</option>
        <option value="basse" {{ request('priorite') === 'basse' ? 'selected' : '' }}>Basse</option>
      </select>
    </div>
    <div class="col-md-2">
      <button type="submit" class="btn btn-sm btn-primary w-100"><i class="bi bi-search me-1"></i>Filtrer</button>
    </div>
    <div class="col-md-1">
      <a href="{{ route('interventions.index') }}" class="btn btn-sm btn-outline-secondary w-100"><i class="bi bi-x-lg"></i></a>
    </div>
  </form>
</div>

<div class="card">
  <div class="card-body p-0">
    <table class="table table-hover mb-0">
      <thead>
        <tr>
          <th>Ticket</th>
          <th>Titre</th>
          <th>Site</th>
          <th>Priorité</th>
          <th>Statut</th>
          <th>Date</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($interventions as $i)
        <tr>
          <td><code style="font-size:11px">{{ $i->numero_ticket }}</code></td>
          <td>
            <span style="font-weight:500">{{ $i->titre }}</span>
            <br><small class="text-muted">{{ ucfirst($i->type) }}
              @if($i->ressource) · {{ $i->ressource->nom_complet }} @endif
            </small>
          </td>
          <td><span class="badge bg-light text-dark border" style="font-size:11px">{{ $i->site->nom }}</span></td>
          <td><span class="badge bg-{{ $i->priorite_badge }}">{{ ucfirst($i->priorite) }}</span></td>
          <td>
            <form method="POST" action="{{ route('interventions.statut', $i) }}">
              @csrf @method('PATCH')
              <select name="statut" class="form-select form-select-sm" style="font-size:12px;width:110px"
                      onchange="this.form.submit()">
                @foreach(['ouvert','en_cours','resolu','ferme'] as $s)
                  <option value="{{ $s }}" {{ $i->statut === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                @endforeach
              </select>
            </form>
          </td>
          <td style="font-size:12px">{{ $i->date_ouverture->format('d/m/Y') }}</td>
          <td>
            <div class="d-flex gap-1">
              <a href="{{ route('interventions.show', $i) }}" class="btn btn-sm btn-outline-primary btn-action">
                <i class="bi bi-eye"></i>
              </a>
              <a href="{{ route('interventions.edit', $i) }}" class="btn btn-sm btn-outline-secondary btn-action">
                <i class="bi bi-pencil"></i>
              </a>
            </div>
          </td>
        </tr>
        @empty
        <tr><td colspan="7">
          <div class="empty-state"><i class="bi bi-tools"></i><p>Aucune intervention</p></div>
        </td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
<div class="mt-3">{{ $interventions->links() }}</div>
@endsection