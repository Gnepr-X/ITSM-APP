@extends('layouts.app')
@section('title', 'Attribution — '.$attribution->numero_fiche)
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h5 class="fw-bold mb-1">
      <i class="bi bi-box-arrow-in-right me-2"></i>Attribution
      <code style="font-size:14px">{{ $attribution->numero_fiche }}</code>
    </h5>
    <small class="text-muted">
      Enregistrée le {{ $attribution->created_at->format('d/m/Y à H:i') }}
    </small>
  </div>
  <div class="d-flex gap-2">
    <a href="{{ route('pdf.attribution', $attribution) }}" target="_blank"
       class="btn btn-outline-danger btn-sm">
      <i class="bi bi-file-pdf me-1"></i>Fiche PDF
    </a>
    @if($attribution->statut === 'actif')
      <a href="{{ route('restitutions.create') }}?attribution_id={{ $attribution->id }}"
         class="btn btn-warning btn-sm">
        <i class="bi bi-box-arrow-left me-1"></i>Restituer
      </a>
    @endif
    <a href="{{ route('attributions.index') }}" class="btn btn-outline-secondary btn-sm">
      <i class="bi bi-arrow-left me-1"></i>Retour
    </a>
  </div>
</div>

<div class="row g-3">

  <!-- Colonne gauche -->
  <div class="col-md-5">

    <!-- Statut global -->
    <div class="card mb-3">
      <div class="card-body text-center py-4">
        @if($attribution->statut === 'actif')
          <div class="mb-2">
            <span class="badge bg-success" style="font-size:14px;padding:8px 20px">
              <i class="bi bi-check-circle me-2"></i>Attribution active
            </span>
          </div>
          <small class="text-muted">
            Depuis le {{ $attribution->date_attribution->format('d/m/Y') }}
            ({{ $attribution->date_attribution->diffForHumans() }})
          </small>
        @else
          <div class="mb-2">
            <span class="badge bg-secondary" style="font-size:14px;padding:8px 20px">
              <i class="bi bi-x-circle me-2"></i>Restituée
            </span>
          </div>
          <small class="text-muted">
            Restituée le {{ $attribution->restitution?->date_restitution->format('d/m/Y') }}
          </small>
        @endif
      </div>
    </div>

    <!-- Bénéficiaire -->
    <div class="card mb-3">
      <div class="card-header fw-semibold">
        <i class="bi bi-person me-2"></i>Bénéficiaire
      </div>
      <div class="card-body">
        <dl class="row mb-0" style="font-size:13.5px">
          <dt class="col-5 text-muted">Nom & Prénom</dt>
          <dd class="col-7 fw-medium">
            <a href="{{ route('ressources.show', $attribution->ressource) }}"
               class="text-decoration-none">
              {{ $attribution->ressource->nom_complet }}
            </a>
          </dd>

          <dt class="col-5 text-muted">Matricule</dt>
          <dd class="col-7"><code>{{ $attribution->ressource->matricule }}</code></dd>

          <dt class="col-5 text-muted">Poste</dt>
          <dd class="col-7">{{ $attribution->ressource->poste }}</dd>

          <dt class="col-5 text-muted">Département</dt>
          <dd class="col-7">{{ $attribution->ressource->departement ?? '—' }}</dd>

          <dt class="col-5 text-muted">Email</dt>
          <dd class="col-7" style="font-size:12px">
            {{ $attribution->ressource->email ?? '—' }}
          </dd>

          <dt class="col-5 text-muted">Téléphone</dt>
          <dd class="col-7">{{ $attribution->ressource->telephone ?? '—' }}</dd>
        </dl>
      </div>
    </div>

    <!-- Site -->
    <div class="card">
      <div class="card-header fw-semibold">
        <i class="bi bi-building me-2"></i>Site d'attribution
      </div>
      <div class="card-body">
        <dl class="row mb-0" style="font-size:13.5px">
          <dt class="col-5 text-muted">Site</dt>
          <dd class="col-7 fw-medium">
            <a href="{{ route('sites.show', $attribution->site) }}" class="text-decoration-none">
              {{ $attribution->site->nom }}
            </a>
          </dd>

          <dt class="col-5 text-muted">Ville</dt>
          <dd class="col-7">{{ $attribution->site->ville }}</dd>

          <dt class="col-5 text-muted">Attribué par</dt>
          <dd class="col-7">{{ $attribution->attribue_par }}</dd>

          <dt class="col-5 text-muted">Date</dt>
          <dd class="col-7">{{ $attribution->date_attribution->format('d/m/Y') }}</dd>
        </dl>
      </div>
    </div>

  </div>

  <!-- Colonne droite -->
  <div class="col-md-7">

    <!-- Équipement -->
    <div class="card mb-3">
      <div class="card-header fw-semibold">
        <i class="bi bi-laptop me-2"></i>Équipement attribué
      </div>
      <div class="card-body">
        <div class="d-flex align-items-start gap-3">
          <div class="bg-light rounded p-3">
            <i class="bi bi-laptop text-primary" style="font-size:32px"></i>
          </div>
          <div class="flex-grow-1">
            <h6 class="fw-bold mb-1">{{ $attribution->equipement->designation }}</h6>
            <div class="row g-2" style="font-size:13px">
              <div class="col-6">
                <span class="text-muted">Code inventaire :</span><br>
                <code>{{ $attribution->equipement->code_inventaire }}</code>
              </div>
              <div class="col-6">
                <span class="text-muted">Type :</span><br>
                <span class="badge bg-light text-dark border">
                  {{ ucfirst($attribution->equipement->type) }}
                </span>
              </div>
              <div class="col-6">
                <span class="text-muted">Marque / Modèle :</span><br>
                {{ $attribution->equipement->marque ?? '—' }}
                {{ $attribution->equipement->modele ?? '' }}
              </div>
              <div class="col-6">
                <span class="text-muted">N° de série :</span><br>
                <code style="font-size:11px">
                  {{ $attribution->equipement->numero_serie ?? '—' }}
                </code>
              </div>
              <div class="col-6">
                <span class="text-muted">Statut actuel :</span><br>
                @php
                  $colors = [
                    'disponible'   => 'success',
                    'attribue'     => 'primary',
                    'en_reparation'=> 'warning',
                    'hors_service' => 'danger',
                  ];
                @endphp
                <span class="badge bg-{{ $colors[$attribution->equipement->statut] ?? 'secondary' }}">
                  {{ ucfirst(str_replace('_',' ',$attribution->equipement->statut)) }}
                </span>
              </div>
              <div class="col-6">
                <span class="text-muted">Site équipement :</span><br>
                {{ $attribution->equipement->site->nom }}
              </div>
            </div>
          </div>
          @if($attribution->equipement->qr_code)
          <div class="text-center">
            <img src="{{ asset('storage/'.$attribution->equipement->qr_code) }}"
                 alt="QR" style="width:80px;height:80px">
            <div style="font-size:10px" class="text-muted mt-1">QR Code</div>
          </div>
          @endif
        </div>

        <div class="mt-3 d-flex gap-2">
          <a href="{{ route('equipements.show', $attribution->equipement) }}"
             class="btn btn-sm btn-outline-primary">
            <i class="bi bi-eye me-1"></i>Voir l'équipement
          </a>
          <a href="{{ route('pdf.attribution', $attribution) }}" target="_blank"
             class="btn btn-sm btn-outline-danger">
            <i class="bi bi-file-pdf me-1"></i>Fiche attribution PDF
          </a>
        </div>
      </div>
    </div>

    <!-- Observation -->
    @if($attribution->observation)
    <div class="card mb-3">
      <div class="card-header fw-semibold">
        <i class="bi bi-sticky me-2"></i>Observations
      </div>
      <div class="card-body" style="font-size:13.5px">
        {{ $attribution->observation }}
      </div>
    </div>
    @endif

    <!-- Restitution (si restituée) -->
    @if($attribution->restitution)
    <div class="card border-success">
      <div class="card-header bg-success text-white fw-semibold">
        <i class="bi bi-box-arrow-left me-2"></i>Détails de la restitution
      </div>
      <div class="card-body">
        <dl class="row mb-0" style="font-size:13.5px">
          <dt class="col-5 text-muted">Date de restitution</dt>
          <dd class="col-7 fw-medium">
            {{ $attribution->restitution->date_restitution->format('d/m/Y') }}
          </dd>

          <dt class="col-5 text-muted">Reçu par</dt>
          <dd class="col-7">{{ $attribution->restitution->recu_par }}</dd>

          <dt class="col-5 text-muted">État de retour</dt>
          <dd class="col-7">
            @php
              $etats = [
                'bon'          => 'success',
                'acceptable'   => 'primary',
                'endommagé'    => 'warning',
                'hors_service' => 'danger',
              ];
            @endphp
            <span class="badge bg-{{ $etats[$attribution->restitution->etat_retour] ?? 'secondary' }}">
              {{ ucfirst($attribution->restitution->etat_retour) }}
            </span>
          </dd>

          @if($attribution->restitution->observation)
            <dt class="col-5 text-muted">Observations</dt>
            <dd class="col-7">{{ $attribution->restitution->observation }}</dd>
          @endif

          <dt class="col-5 text-muted">Durée d'attribution</dt>
          <dd class="col-7">
            {{ $attribution->date_attribution->diffInDays($attribution->restitution->date_restitution) }} jour(s)
          </dd>
        </dl>

        <div class="mt-3">
          <a href="{{ route('pdf.restitution', $attribution) }}" target="_blank"
             class="btn btn-sm btn-outline-danger">
            <i class="bi bi-file-pdf me-1"></i>Fiche restitution PDF
          </a>
        </div>
      </div>
    </div>
    @endif

  </div>
</div>
@endsection