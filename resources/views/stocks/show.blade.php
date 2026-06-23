@extends('layouts.app')
@section('title', 'Mouvement de stock')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h5 class="fw-bold mb-1">
      <i class="bi bi-archive me-2"></i>Détail du mouvement
    </h5>
    <small class="text-muted">
      Enregistré le {{ $stock->created_at->format('d/m/Y à H:i') }}
    </small>
  </div>
  <div class="d-flex gap-2">
    <a href="{{ route('stocks.edit', $stock) }}" class="btn btn-outline-secondary btn-sm">
      <i class="bi bi-pencil me-1"></i>Modifier
    </a>
    <a href="{{ route('stocks.index') }}" class="btn btn-outline-secondary btn-sm">
      <i class="bi bi-arrow-left me-1"></i>Retour
    </a>
  </div>
</div>

<div class="row g-3">

  <!-- Colonne gauche -->
  <div class="col-md-4">

    <!-- Type de mouvement -->
    <div class="card mb-3">
      <div class="card-body text-center py-4">
        @if($stock->mouvement === 'entree')
          <div style="font-size:48px;color:#16a34a">
            <i class="bi bi-arrow-down-circle-fill"></i>
          </div>
          <div class="fw-bold mt-2" style="font-size:20px;color:#16a34a">Entrée</div>
          <small class="text-muted">Ajout en stock</small>
        @else
          <div style="font-size:48px;color:#dc2626">
            <i class="bi bi-arrow-up-circle-fill"></i>
          </div>
          <div class="fw-bold mt-2" style="font-size:20px;color:#dc2626">Sortie</div>
          <small class="text-muted">Retrait du stock</small>
        @endif
        <div class="mt-3">
          <span class="badge bg-light text-dark border" style="font-size:16px;padding:8px 20px">
            Qté : <strong>{{ $stock->quantite }}</strong>
          </span>
        </div>
      </div>
    </div>

    <!-- Infos du mouvement -->
    <div class="card">
      <div class="card-header fw-semibold">
        <i class="bi bi-info-circle me-2"></i>Informations
      </div>
      <div class="card-body">
        <dl class="row mb-0" style="font-size:13.5px">
          <dt class="col-5 text-muted">Date</dt>
          <dd class="col-7">
            <strong>{{ $stock->date_mouvement->format('d/m/Y') }}</strong>
          </dd>

          <dt class="col-5 text-muted">Site</dt>
          <dd class="col-7">
            <a href="{{ route('sites.show', $stock->site) }}" class="text-decoration-none">
              <span class="badge bg-light text-dark border">
                <i class="bi bi-building me-1"></i>{{ $stock->site->nom }}
              </span>
            </a>
          </dd>

          <dt class="col-5 text-muted">Effectué par</dt>
          <dd class="col-7">{{ $stock->effectue_par }}</dd>

          <dt class="col-5 text-muted">Motif</dt>
          <dd class="col-7">{{ $stock->motif }}</dd>
        </dl>

        @if($stock->notes)
          <div class="alert alert-light border mt-3 mb-0" style="font-size:13px">
            <i class="bi bi-sticky me-2"></i>{{ $stock->notes }}
          </div>
        @endif
      </div>
    </div>

  </div>

  <!-- Colonne droite -->
  <div class="col-md-8">

    <!-- Équipement concerné -->
    <div class="card mb-3">
      <div class="card-header fw-semibold">
        <i class="bi bi-laptop me-2"></i>Équipement concerné
      </div>
      <div class="card-body">
        <div class="d-flex align-items-start gap-3">
          <div class="bg-light rounded p-3">
            <i class="bi bi-laptop text-primary" style="font-size:32px"></i>
          </div>
          <div class="flex-grow-1">
            <h6 class="fw-bold mb-2">{{ $stock->equipement->designation }}</h6>
            <div class="row g-2" style="font-size:13px">
              <div class="col-md-6">
                <span class="text-muted">Code inventaire :</span><br>
                <code>{{ $stock->equipement->code_inventaire }}</code>
              </div>
              <div class="col-md-6">
                <span class="text-muted">Type :</span><br>
                <span class="badge bg-light text-dark border">
                  {{ ucfirst($stock->equipement->type) }}
                </span>
              </div>
              <div class="col-md-6">
                <span class="text-muted">Marque / Modèle :</span><br>
                {{ $stock->equipement->marque ?? '—' }}
                {{ $stock->equipement->modele ?? '' }}
              </div>
              <div class="col-md-6">
                <span class="text-muted">N° de série :</span><br>
                <code style="font-size:11px">
                  {{ $stock->equipement->numero_serie ?? '—' }}
                </code>
              </div>
              <div class="col-md-6">
                <span class="text-muted">Statut actuel :</span><br>
                @php
                  $colors = [
                    'disponible'    => 'success',
                    'attribue'      => 'primary',
                    'en_reparation' => 'warning',
                    'hors_service'  => 'danger',
                  ];
                @endphp
                <span class="badge bg-{{ $colors[$stock->equipement->statut] ?? 'secondary' }}">
                  {{ ucfirst(str_replace('_',' ', $stock->equipement->statut)) }}
                </span>
              </div>
              <div class="col-md-6">
                <span class="text-muted">Site équipement :</span><br>
                {{ $stock->equipement->site->nom }}
              </div>
              @if($stock->equipement->date_acquisition)
              <div class="col-md-6">
                <span class="text-muted">Date d'acquisition :</span><br>
                {{ $stock->equipement->date_acquisition->format('d/m/Y') }}
              </div>
              @endif
              @if($stock->equipement->valeur)
              <div class="col-md-6">
                <span class="text-muted">Valeur :</span><br>
                {{ number_format($stock->equipement->valeur, 0, ',', ' ') }} FCFA
              </div>
              @endif
            </div>
          </div>
        </div>

        <div class="mt-3 d-flex gap-2">
          <a href="{{ route('equipements.show', $stock->equipement) }}"
             class="btn btn-sm btn-outline-primary">
            <i class="bi bi-eye me-1"></i>Voir la fiche équipement
          </a>
          @if($stock->equipement->qr_code && \Storage::disk('public')->exists($stock->equipement->qr_code))
            <a href="{{ route('equipements.qrcode', $stock->equipement) }}"
               target="_blank" class="btn btn-sm btn-outline-secondary">
              <i class="bi bi-qr-code me-1"></i>QR Code
            </a>
          @endif
        </div>
      </div>
    </div>

    <!-- Historique des mouvements de cet équipement -->
    <div class="card">
      <div class="card-header fw-semibold">
        <i class="bi bi-clock-history me-2"></i>
        Autres mouvements de cet équipement
      </div>
      <div class="card-body p-0">
        @php
          $autresMouvements = App\Models\Stock::where('equipement_id', $stock->equipement_id)
              ->where('id', '!=', $stock->id)
              ->with('site')
              ->orderByDesc('date_mouvement')
              ->take(10)
              ->get();
        @endphp
        @forelse($autresMouvements as $m)
        <div class="d-flex align-items-center gap-3 px-4 py-3 border-bottom">
          <div>
            @if($m->mouvement === 'entree')
              <i class="bi bi-arrow-down-circle-fill text-success" style="font-size:20px"></i>
            @else
              <i class="bi bi-arrow-up-circle-fill text-danger" style="font-size:20px"></i>
            @endif
          </div>
          <div class="flex-grow-1">
            <div style="font-size:13.5px;font-weight:500">{{ $m->motif }}</div>
            <small class="text-muted">
              {{ $m->site->nom }} · {{ $m->effectue_par }}
            </small>
          </div>
          <div class="text-end" style="font-size:12px">
            <div>Qté : <strong>{{ $m->quantite }}</strong></div>
            <div class="text-muted">{{ $m->date_mouvement->format('d/m/Y') }}</div>
          </div>
          <a href="{{ route('stocks.show', $m) }}"
             class="btn btn-sm btn-outline-secondary btn-action">
            <i class="bi bi-eye"></i>
          </a>
        </div>
        @empty
        <div class="empty-state py-4">
          <i class="bi bi-archive" style="font-size:32px"></i>
          <p class="mt-2" style="font-size:13px">Aucun autre mouvement pour cet équipement</p>
        </div>
        @endforelse
      </div>
    </div>

  </div>
</div>
@endsection