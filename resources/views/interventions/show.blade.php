@extends('layouts.app')
@section('title', 'Intervention — '.$intervention->numero_ticket)
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h5 class="fw-bold mb-1">
      <i class="bi bi-tools me-2"></i>Ticket
      <code style="font-size:14px">{{ $intervention->numero_ticket }}</code>
    </h5>
    <small class="text-muted">
      Ouvert le {{ $intervention->date_ouverture->format('d/m/Y') }}
      @if($intervention->duree)
        · Durée : {{ $intervention->duree }}
      @endif
    </small>
  </div>
  <div class="d-flex gap-2">
    @if($intervention->statut !== 'ferme')
      <a href="{{ route('interventions.edit', $intervention) }}"
         class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-pencil me-1"></i>Modifier
      </a>
    @endif
    <a href="{{ route('pdf.interventions') }}?statut={{ $intervention->statut }}"
       target="_blank" class="btn btn-outline-danger btn-sm">
      <i class="bi bi-file-pdf me-1"></i>PDF
    </a>
    <a href="{{ route('interventions.index') }}" class="btn btn-outline-secondary btn-sm">
      <i class="bi bi-arrow-left me-1"></i>Retour
    </a>
  </div>
</div>

<div class="row g-3">

  <!-- Colonne gauche -->
  <div class="col-md-4">

    <!-- Statut & Priorité -->
    <div class="card mb-3">
      <div class="card-body text-center py-4">
        <div class="mb-3">
          <span class="badge bg-{{ $intervention->priorite_badge }}"
                style="font-size:13px;padding:7px 18px">
            @php
              $icons = ['critique'=>'🔴','haute'=>'🟠','normale'=>'🔵','basse'=>'⚪'];
            @endphp
            {{ $icons[$intervention->priorite] ?? '' }}
            Priorité {{ ucfirst($intervention->priorite) }}
          </span>
        </div>
        <div>
          <span class="badge bg-{{ $intervention->statut_badge }}"
                style="font-size:13px;padding:7px 18px">
            @php
              $statutIcons = ['ouvert'=>'🔴','en_cours'=>'🟡','resolu'=>'🟢','ferme'=>'⚫'];
            @endphp
            {{ $statutIcons[$intervention->statut] ?? '' }}
            {{ ucfirst(str_replace('_',' ',$intervention->statut)) }}
          </span>
        </div>

        @if($intervention->statut !== 'ferme' && $intervention->statut !== 'resolu')
          <!-- Changement rapide de statut -->
          <div class="mt-3">
            <form method="POST" action="{{ route('interventions.statut', $intervention) }}">
              @csrf @method('PATCH')
              <select name="statut" class="form-select form-select-sm mb-2"
                      style="font-size:12px">
                @foreach(['ouvert','en_cours','resolu','ferme'] as $s)
                  <option value="{{ $s }}"
                    {{ $intervention->statut === $s ? 'selected' : '' }}>
                    {{ ucfirst(str_replace('_',' ',$s)) }}
                  </option>
                @endforeach
              </select>
              <button type="submit" class="btn btn-sm btn-primary w-100">
                <i class="bi bi-arrow-repeat me-1"></i>Changer le statut
              </button>
            </form>
          </div>
        @endif
      </div>
    </div>

    <!-- Informations générales -->
    <div class="card mb-3">
      <div class="card-header fw-semibold">
        <i class="bi bi-info-circle me-2"></i>Informations
      </div>
      <div class="card-body">
        <dl class="row mb-0" style="font-size:13.5px">
          <dt class="col-5 text-muted">Type</dt>
          <dd class="col-7">
            <span class="badge bg-light text-dark border">
              {{ ucfirst($intervention->type) }}
            </span>
          </dd>

          <dt class="col-5 text-muted">Site</dt>
          <dd class="col-7">
            <a href="{{ route('sites.show', $intervention->site) }}"
               class="text-decoration-none">
              <span class="badge bg-light text-dark border">
                <i class="bi bi-building me-1"></i>{{ $intervention->site->nom }}
              </span>
            </a>
          </dd>

          <dt class="col-5 text-muted">Ouverture</dt>
          <dd class="col-7">{{ $intervention->date_ouverture->format('d/m/Y') }}</dd>

          <dt class="col-5 text-muted">Résolution</dt>
          <dd class="col-7">
            {{ $intervention->date_resolution?->format('d/m/Y') ?? '—' }}
          </dd>

          @if($intervention->duree)
            <dt class="col-5 text-muted">Durée</dt>
            <dd class="col-7">{{ $intervention->duree }}</dd>
          @endif
        </dl>
      </div>
    </div>

    <!-- Ressource concernée -->
    @if($intervention->ressource)
    <div class="card mb-3">
      <div class="card-header fw-semibold">
        <i class="bi bi-person me-2"></i>Ressource concernée
      </div>
      <div class="card-body">
        <div class="d-flex align-items-center gap-3 mb-2">
          <div style="width:40px;height:40px;background:#1a2035;color:#fff;
                      border-radius:50%;display:flex;align-items:center;
                      justify-content:center;font-weight:bold;font-size:15px">
            {{ strtoupper(substr($intervention->ressource->prenom,0,1)) }}{{ strtoupper(substr($intervention->ressource->nom,0,1)) }}
          </div>
          <div>
            <div class="fw-bold" style="font-size:14px">
              {{ $intervention->ressource->nom_complet }}
            </div>
            <small class="text-muted">{{ $intervention->ressource->poste }}</small>
          </div>
        </div>
        <dl class="row mb-0" style="font-size:13px">
          <dt class="col-5 text-muted">Matricule</dt>
          <dd class="col-7"><code>{{ $intervention->ressource->matricule }}</code></dd>

          @if($intervention->ressource->email)
            <dt class="col-5 text-muted">Email</dt>
            <dd class="col-7" style="font-size:12px">{{ $intervention->ressource->email }}</dd>
          @endif

          @if($intervention->ressource->telephone)
            <dt class="col-5 text-muted">Téléphone</dt>
            <dd class="col-7">{{ $intervention->ressource->telephone }}</dd>
          @endif
        </dl>
        <div class="mt-2">
          <a href="{{ route('ressources.show', $intervention->ressource) }}"
             class="btn btn-sm btn-outline-primary w-100">
            <i class="bi bi-eye me-1"></i>Voir la fiche ressource
          </a>
        </div>
      </div>
    </div>
    @endif

    <!-- Équipement concerné -->
    @if($intervention->equipement)
    <div class="card">
      <div class="card-header fw-semibold">
        <i class="bi bi-laptop me-2"></i>Équipement concerné
      </div>
      <div class="card-body">
        <div style="font-size:13.5px">
          <div class="fw-bold mb-1">{{ $intervention->equipement->designation }}</div>
          <div class="mb-1">
            <code style="font-size:11px">{{ $intervention->equipement->code_inventaire }}</code>
          </div>
          <div class="mb-1 text-muted">
            {{ $intervention->equipement->marque }}
            {{ $intervention->equipement->modele }}
          </div>
          @php
            $colors = [
              'disponible'    => 'success',
              'attribue'      => 'primary',
              'en_reparation' => 'warning',
              'hors_service'  => 'danger',
            ];
          @endphp
          <span class="badge bg-{{ $colors[$intervention->equipement->statut] ?? 'secondary' }}">
            {{ ucfirst(str_replace('_',' ',$intervention->equipement->statut)) }}
          </span>
        </div>
        <div class="mt-2">
          <a href="{{ route('equipements.show', $intervention->equipement) }}"
             class="btn btn-sm btn-outline-primary w-100">
            <i class="bi bi-eye me-1"></i>Voir la fiche équipement
          </a>
        </div>
      </div>
    </div>
    @endif

  </div>

  <!-- Colonne droite -->
  <div class="col-md-8">

    <!-- Titre & Description -->
    <div class="card mb-3">
      <div class="card-header fw-semibold">
        <i class="bi bi-card-text me-2"></i>Description du problème
      </div>
      <div class="card-body">
        <h6 class="fw-bold mb-3" style="font-size:15px">{{ $intervention->titre }}</h6>
        <div style="font-size:13.5px;line-height:1.7;background:#f8f9fa;
                    padding:16px;border-radius:8px;border-left:4px solid #1a2035">
          {{ $intervention->description }}
        </div>
      </div>
    </div>

    <!-- Solution (si résolue) -->
    @if($intervention->solution)
    <div class="card mb-3" style="border-left:4px solid #16a34a">
      <div class="card-header fw-semibold text-success">
        <i class="bi bi-check-circle-fill me-2"></i>Solution apportée
      </div>
      <div class="card-body">
        <div style="font-size:13.5px;line-height:1.7;background:#f0fdf4;
                    padding:16px;border-radius:8px">
          {{ $intervention->solution }}
        </div>
        @if($intervention->date_resolution)
          <div class="mt-2 text-muted" style="font-size:12px">
            <i class="bi bi-calendar-check me-1"></i>
            Résolu le {{ $intervention->date_resolution->format('d/m/Y') }}
            @if($intervention->duree) · Durée totale : {{ $intervention->duree }} @endif
          </div>
        @endif
      </div>
    </div>
    @endif

    <!-- Formulaire de résolution rapide -->
    @if(in_array($intervention->statut, ['ouvert','en_cours']))
    <div class="card mb-3">
      <div class="card-header fw-semibold">
        <i class="bi bi-patch-check me-2"></i>Saisir la solution
      </div>
      <div class="card-body">
        <form method="POST" action="{{ route('interventions.update', $intervention) }}">
          @csrf @method('PUT')
          {{-- Champs cachés pour conserver les valeurs existantes --}}
          <input type="hidden" name="site_id"        value="{{ $intervention->site_id }}">
          <input type="hidden" name="ressource_id"   value="{{ $intervention->ressource_id }}">
          <input type="hidden" name="equipement_id"  value="{{ $intervention->equipement_id }}">
          <input type="hidden" name="type"            value="{{ $intervention->type }}">
          <input type="hidden" name="titre"           value="{{ $intervention->titre }}">
          <input type="hidden" name="description"     value="{{ $intervention->description }}">
          <input type="hidden" name="priorite"        value="{{ $intervention->priorite }}">
          <input type="hidden" name="statut"          value="resolu">
          <input type="hidden" name="date_ouverture"  value="{{ $intervention->date_ouverture->format('Y-m-d') }}">
          <input type="hidden" name="date_resolution" value="{{ date('Y-m-d') }}">

          <div class="mb-3">
            <label class="form-label fw-medium">Solution apportée <span class="text-danger">*</span></label>
            <textarea name="solution" class="form-control" rows="4"
                      placeholder="Décrire la solution mise en place..." required></textarea>
          </div>
          <button type="submit" class="btn btn-success w-100">
            <i class="bi bi-check-circle me-1"></i>Marquer comme résolu
          </button>
        </form>
      </div>
    </div>
    @endif

    <!-- Timeline -->
    <div class="card">
      <div class="card-header fw-semibold">
        <i class="bi bi-clock-history me-2"></i>Chronologie
      </div>
      <div class="card-body">
        <div style="position:relative;padding-left:24px">

          <!-- Ouverture -->
          <div style="position:relative;margin-bottom:20px">
            <div style="position:absolute;left:-24px;top:3px;width:12px;height:12px;
                        background:#dc2626;border-radius:50%;border:2px solid #fff;
                        box-shadow:0 0 0 2px #dc2626"></div>
            <div style="font-size:13.5px">
              <span class="fw-bold">Ticket ouvert</span>
              <span class="text-muted ms-2" style="font-size:12px">
                {{ $intervention->date_ouverture->format('d/m/Y') }}
              </span>
            </div>
            <div class="text-muted" style="font-size:12px">
              Priorité {{ $intervention->priorite }} · {{ ucfirst($intervention->type) }}
            </div>
          </div>

          <!-- En cours -->
          @if(in_array($intervention->statut, ['en_cours','resolu','ferme']))
          <div style="position:relative;margin-bottom:20px">
            <div style="position:absolute;left:-24px;top:3px;width:12px;height:12px;
                        background:#d97706;border-radius:50%;border:2px solid #fff;
                        box-shadow:0 0 0 2px #d97706"></div>
            <div style="font-size:13.5px">
              <span class="fw-bold">Prise en charge</span>
            </div>
            <div class="text-muted" style="font-size:12px">Ticket en cours de traitement</div>
          </div>
          @endif

          <!-- Résolu -->
          @if(in_array($intervention->statut, ['resolu','ferme']))
          <div style="position:relative;margin-bottom:20px">
            <div style="position:absolute;left:-24px;top:3px;width:12px;height:12px;
                        background:#16a34a;border-radius:50%;border:2px solid #fff;
                        box-shadow:0 0 0 2px #16a34a"></div>
            <div style="font-size:13.5px">
              <span class="fw-bold">Résolu</span>
              @if($intervention->date_resolution)
                <span class="text-muted ms-2" style="font-size:12px">
                  {{ $intervention->date_resolution->format('d/m/Y') }}
                </span>
              @endif
            </div>
            @if($intervention->duree)
              <div class="text-muted" style="font-size:12px">
                Durée de résolution : {{ $intervention->duree }}
              </div>
            @endif
          </div>
          @endif

          <!-- Fermé -->
          @if($intervention->statut === 'ferme')
          <div style="position:relative">
            <div style="position:absolute;left:-24px;top:3px;width:12px;height:12px;
                        background:#6b7280;border-radius:50%;border:2px solid #fff;
                        box-shadow:0 0 0 2px #6b7280"></div>
            <div style="font-size:13.5px">
              <span class="fw-bold">Ticket fermé</span>
            </div>
            <div class="text-muted" style="font-size:12px">Intervention clôturée</div>
          </div>
          @endif

        </div>
      </div>
    </div>

  </div>
</div>
@endsection