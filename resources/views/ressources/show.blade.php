@extends('layouts.app')
@section('title', $ressource->nom_complet)
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h5 class="fw-bold mb-1">
      <i class="bi bi-person-circle me-2"></i>{{ $ressource->nom_complet }}
    </h5>
    <small class="text-muted">
      {{ $ressource->poste }}
      @if($ressource->departement) · {{ $ressource->departement }} @endif
      · {{ $ressource->site->nom }}
    </small>
  </div>
  <div class="d-flex gap-2">
    <a href="{{ route('pdf.ressources', $ressource->site) }}" target="_blank"
       class="btn btn-outline-danger btn-sm">
      <i class="bi bi-file-pdf me-1"></i>PDF
    </a>
    <a href="{{ route('attributions.create') }}?ressource_id={{ $ressource->id }}"
       class="btn btn-outline-primary btn-sm">
      <i class="bi bi-box-arrow-in-right me-1"></i>Attribuer équipement
    </a>
    <a href="{{ route('ressources.edit', $ressource) }}"
       class="btn btn-outline-secondary btn-sm">
      <i class="bi bi-pencil me-1"></i>Modifier
    </a>
  </div>
</div>

<div class="row g-3">

  <!-- Infos personnelles -->
  <div class="col-md-4">
    <div class="card mb-3">
      <div class="card-header fw-semibold">
        <i class="bi bi-person me-2"></i>Informations
      </div>
      <div class="card-body">
        <dl class="row mb-0" style="font-size:13.5px">
          <dt class="col-5 text-muted">Matricule</dt>
          <dd class="col-7"><code>{{ $ressource->matricule }}</code></dd>

          <dt class="col-5 text-muted">Nom</dt>
          <dd class="col-7 fw-medium">{{ $ressource->nom }}</dd>

          <dt class="col-5 text-muted">Prénom</dt>
          <dd class="col-7">{{ $ressource->prenom }}</dd>

          <dt class="col-5 text-muted">Poste</dt>
          <dd class="col-7">{{ $ressource->poste }}</dd>

          <dt class="col-5 text-muted">Département</dt>
          <dd class="col-7">{{ $ressource->departement ?? '—' }}</dd>

          <dt class="col-5 text-muted">Site</dt>
          <dd class="col-7">
            <a href="{{ route('sites.show', $ressource->site) }}" class="text-decoration-none">
              <span class="badge bg-light text-dark border">
                <i class="bi bi-building me-1"></i>{{ $ressource->site->nom }}
              </span>
            </a>
          </dd>

          <dt class="col-5 text-muted">Email</dt>
          <dd class="col-7">
            @if($ressource->email)
              <a href="mailto:{{ $ressource->email }}" style="font-size:12px">
                {{ $ressource->email }}
              </a>
            @else
              <span class="text-muted">—</span>
            @endif
          </dd>

          <dt class="col-5 text-muted">Téléphone</dt>
          <dd class="col-7">{{ $ressource->telephone ?? '—' }}</dd>
        </dl>
      </div>
    </div>

    <!-- Résumé équipements -->
    <div class="card">
      <div class="card-header fw-semibold">
        <i class="bi bi-laptop me-2"></i>Résumé équipements
      </div>
      <div class="card-body">
        <div class="row g-2 text-center">
          <div class="col-6">
            <div class="bg-light rounded p-3">
              <div class="fw-bold text-primary" style="font-size:22px">
                {{ $equipements_actifs->count() }}
              </div>
              <small class="text-muted">En cours</small>
            </div>
          </div>
          <div class="col-6">
            <div class="bg-light rounded p-3">
              <div class="fw-bold text-secondary" style="font-size:22px">
                {{ $ressource->attributions->where('statut','restitue')->count() }}
              </div>
              <small class="text-muted">Restitués</small>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Équipements actifs -->
  <div class="col-md-8">
    <div class="card mb-3">
      <div class="card-header d-flex justify-content-between align-items-center">
        <span class="fw-semibold">
          <i class="bi bi-box-arrow-in-right me-2"></i>Équipements attribués actifs
        </span>
        <a href="{{ route('attributions.create') }}?ressource_id={{ $ressource->id }}"
           class="btn btn-sm btn-primary btn-action">
          <i class="bi bi-plus-lg me-1"></i>Attribuer
        </a>
      </div>
      <div class="card-body p-0">
        @forelse($equipements_actifs as $eq)
        @php
          $attribution = $ressource->attributions
            ->where('statut','actif')
            ->firstWhere('equipement_id', $eq->id);
        @endphp
        <div class="d-flex align-items-center gap-3 px-4 py-3 border-bottom">
          <div class="rounded p-2 bg-light">
            <i class="bi bi-laptop text-primary" style="font-size:20px"></i>
          </div>
          <div class="flex-grow-1">
            <div style="font-weight:500;font-size:14px">{{ $eq->designation }}</div>
            <small class="text-muted">
              <code style="font-size:10px">{{ $eq->code_inventaire }}</code>
              · {{ $eq->marque }} {{ $eq->modele }}
              @if($eq->numero_serie) · S/N: {{ $eq->numero_serie }} @endif
            </small>
          </div>
          <div class="text-end" style="font-size:12px">
            @if($attribution)
              <div class="text-muted">Depuis le {{ $attribution->date_attribution->format('d/m/Y') }}</div>
              <div><code style="font-size:10px">{{ $attribution->numero_fiche }}</code></div>
            @endif
          </div>
          <div class="d-flex gap-1">
            @if($attribution)
              <a href="{{ route('pdf.attribution', $attribution) }}" target="_blank"
                 class="btn btn-sm btn-outline-danger btn-action" title="Fiche PDF">
                <i class="bi bi-file-pdf"></i>
              </a>
              <a href="{{ route('restitutions.create') }}?attribution_id={{ $attribution->id }}"
                 class="btn btn-sm btn-outline-warning btn-action" title="Restituer">
                <i class="bi bi-box-arrow-left"></i>
              </a>
            @endif
            <a href="{{ route('equipements.show', $eq) }}"
               class="btn btn-sm btn-outline-primary btn-action">
              <i class="bi bi-eye"></i>
            </a>
          </div>
        </div>
        @empty
        <div class="empty-state py-4">
          <i class="bi bi-inbox" style="font-size:36px"></i>
          <p class="mt-2" style="font-size:13px">Aucun équipement attribué actuellement</p>
        </div>
        @endforelse
      </div>
    </div>

    <!-- Historique des attributions -->
    @if($ressource->attributions->where('statut','restitue')->count() > 0)
    <div class="card mb-3">
      <div class="card-header fw-semibold">
        <i class="bi bi-clock-history me-2"></i>Historique des attributions
      </div>
      <div class="card-body p-0">
        <table class="table table-sm table-hover mb-0">
          <thead>
            <tr>
              <th>N° Fiche</th>
              <th>Équipement</th>
              <th>Attribution</th>
              <th>Restitution</th>
              <th>État retour</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            @foreach($ressource->attributions->where('statut','restitue') as $attr)
            @php
              $etats = [
                'bon'          => 'success',
                'acceptable'   => 'primary',
                'endommagé'    => 'warning',
                'hors_service' => 'danger',
              ];
            @endphp
            <tr>
              <td><code style="font-size:10px">{{ $attr->numero_fiche }}</code></td>
              <td style="font-size:12px">
                {{ $attr->equipement->designation }}
                <br>
                <span class="text-muted" style="font-size:10px">
                  {{ $attr->equipement->code_inventaire }}
                </span>
              </td>
              <td style="font-size:12px">
                {{ $attr->date_attribution->format('d/m/Y') }}
              </td>
              <td style="font-size:12px">
                {{ $attr->restitution?->date_restitution->format('d/m/Y') ?? '—' }}
              </td>
              <td>
                @if($attr->restitution)
                  <span class="badge bg-{{ $etats[$attr->restitution->etat_retour] ?? 'secondary' }}">
                    {{ ucfirst($attr->restitution->etat_retour) }}
                  </span>
                @else
                  <span class="text-muted">—</span>
                @endif
              </td>
              <td>
                <a href="{{ route('pdf.attribution', $attr) }}" target="_blank"
                   class="btn btn-sm btn-outline-danger btn-action">
                  <i class="bi bi-file-pdf"></i>
                </a>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
    @endif

    <!-- Interventions liées -->
    @if($ressource->interventions->count() > 0)
    <div class="card">
      <div class="card-header fw-semibold">
        <i class="bi bi-tools me-2"></i>Interventions liées
      </div>
      <div class="card-body p-0">
        <table class="table table-sm table-hover mb-0">
          <thead>
            <tr>
              <th>Ticket</th>
              <th>Titre</th>
              <th>Site</th>
              <th>Priorité</th>
              <th>Statut</th>
              <th>Date</th>
            </tr>
          </thead>
          <tbody>
            @foreach($ressource->interventions as $i)
            <tr>
              <td>
                <a href="{{ route('interventions.show', $i) }}" style="font-size:11px">
                  {{ $i->numero_ticket }}
                </a>
              </td>
              <td style="font-size:12px">{{ Str::limit($i->titre, 35) }}</td>
              <td style="font-size:11px">{{ $i->site->nom }}</td>
              <td>
                <span class="badge bg-{{ $i->priorite_badge }}" style="font-size:10px">
                  {{ ucfirst($i->priorite) }}
                </span>
              </td>
              <td>
                <span class="badge bg-{{ $i->statut_badge }}" style="font-size:10px">
                  {{ ucfirst($i->statut) }}
                </span>
              </td>
              <td style="font-size:11px">
                {{ $i->date_ouverture->format('d/m/Y') }}
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
    @endif

  </div>
</div>
@endsection