@extends('layouts.app')
@section('title', $equipement->designation)
@section('content')

<div class="row g-3">
  <div class="col-md-8">
    <div class="card mb-3">
      <div class="card-header d-flex justify-content-between align-items-center">
        <span class="fw-semibold"><i class="bi bi-laptop me-2"></i>{{ $equipement->designation }}</span>
        <div class="d-flex gap-2">
          <a href="{{ route('equipements.edit', $equipement) }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-pencil me-1"></i>Modifier
          </a>
          @if($equipement->statut === 'disponible')
            <a href="{{ route('attributions.create') }}?equipement_id={{ $equipement->id }}"
               class="btn btn-sm btn-primary">
              <i class="bi bi-box-arrow-in-right me-1"></i>Attribuer
            </a>
          @endif
        </div>
      </div>
      <div class="card-body">
        <div class="row g-3">
          <div class="col-md-6">
            <dl class="row mb-0" style="font-size:13.5px">
              <dt class="col-5 text-muted">Code inventaire</dt>
              <dd class="col-7"><code>{{ $equipement->code_inventaire }}</code></dd>
              <dt class="col-5 text-muted">Type</dt>
              <dd class="col-7"><span class="badge bg-light text-dark border">{{ ucfirst($equipement->type) }}</span></dd>
              <dt class="col-5 text-muted">Marque</dt>
              <dd class="col-7">{{ $equipement->marque ?? '—' }}</dd>
              <dt class="col-5 text-muted">Modèle</dt>
              <dd class="col-7">{{ $equipement->modele ?? '—' }}</dd>
              <dt class="col-5 text-muted">N° de série</dt>
              <dd class="col-7"><code style="font-size:11px">{{ $equipement->numero_serie ?? '—' }}</code></dd>
            </dl>
          </div>
          <div class="col-md-6">
            <dl class="row mb-0" style="font-size:13.5px">
              <dt class="col-5 text-muted">Statut</dt>
              <dd class="col-7">
                @php $colors = ['disponible'=>'success','attribue'=>'primary','en_reparation'=>'warning','hors_service'=>'danger']; @endphp
                <span class="badge bg-{{ $colors[$equipement->statut] ?? 'secondary' }}">
                  {{ ucfirst(str_replace('_',' ',$equipement->statut)) }}
                </span>
              </dd>
              <dt class="col-5 text-muted">Site</dt>
              <dd class="col-7">{{ $equipement->site->nom }}</dd>
              <dt class="col-5 text-muted">Acquisition</dt>
              <dd class="col-7">{{ $equipement->date_acquisition?->format('d/m/Y') ?? '—' }}</dd>
              <dt class="col-5 text-muted">Valeur</dt>
              <dd class="col-7">{{ $equipement->valeur ? number_format($equipement->valeur, 0, ',', ' ').' FCFA' : '—' }}</dd>
            </dl>
          </div>
        </div>
        @if($equipement->notes)
          <div class="alert alert-light mt-3 mb-0" style="font-size:13px">
            <i class="bi bi-sticky me-2"></i>{{ $equipement->notes }}
          </div>
        @endif
      </div>
    </div>

    <!-- Attribution active -->
    @if($attribution_active)
    <div class="card mb-3 border-primary">
      <div class="card-header bg-primary text-white fw-semibold">
        <i class="bi bi-person-check me-2"></i>Attribution active
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-6" style="font-size:13.5px">
            <strong>{{ $attribution_active->ressource->nom_complet }}</strong><br>
            <span class="text-muted">{{ $attribution_active->ressource->poste }}</span><br>
            <span class="text-muted">{{ $attribution_active->ressource->site->nom }}</span>
          </div>
          <div class="col-md-6" style="font-size:13.5px">
            Depuis le {{ $attribution_active->date_attribution->format('d/m/Y') }}<br>
            <small class="text-muted">Fiche N° {{ $attribution_active->numero_fiche }}</small>
          </div>
        </div>
        <div class="d-flex gap-2 mt-3">
          <a href="{{ route('pdf.attribution', $attribution_active) }}" target="_blank"
             class="btn btn-sm btn-outline-danger">
            <i class="bi bi-file-pdf me-1"></i>Fiche PDF
          </a>
          <a href="{{ route('restitutions.create') }}?attribution_id={{ $attribution_active->id }}"
             class="btn btn-sm btn-warning">
            <i class="bi bi-box-arrow-left me-1"></i>Restituer
          </a>
        </div>
      </div>
    </div>
    @endif

    <!-- Historique interventions -->
    @if($equipement->interventions->count())
    <div class="card">
      <div class="card-header fw-semibold">Historique des interventions</div>
      <div class="card-body p-0">
        <table class="table table-sm mb-0">
          <thead><tr><th>Ticket</th><th>Type</th><th>Statut</th><th>Date</th></tr></thead>
          <tbody>
            @foreach($equipement->interventions as $i)
            <tr>
              <td><a href="{{ route('interventions.show', $i) }}">{{ $i->numero_ticket }}</a></td>
              <td>{{ ucfirst($i->type) }}</td>
              <td><span class="badge bg-{{ $i->statut_badge }}">{{ ucfirst($i->statut) }}</span></td>
              <td style="font-size:12px">{{ $i->date_ouverture->format('d/m/Y') }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
    @endif
  </div>

  <!-- QR Code -->
  <div class="col-md-4">
    <div class="card text-center">
      <div class="card-header fw-semibold">QR Code</div>
      <div class="card-body">
        <!-- @if($equipement->qr_code)
          <img src="{{ asset('storage/'.$equipement->qr_code) }}"
               alt="QR Code {{ $equipement->code_inventaire }}"
               class="img-fluid" style="max-width:180px">
          <p class="text-muted mt-2 mb-3" style="font-size:12px">
            Scanner pour afficher les informations techniques
          </p>
          <a href="{{ route('equipements.qrcode', $equipement) }}" target="_blank"
             class="btn btn-sm btn-outline-primary w-100">
            <i class="bi bi-printer me-1"></i>Imprimer QR
          </a>
        @else
          <div class="empty-state py-4">
            <i class="bi bi-qr-code" style="font-size:36px"></i>
            <p class="mt-2" style="font-size:13px">QR code non généré</p>
          </div>
        @endif -->
        @if($equipement->qr_code && \Storage::disk('public')->exists($equipement->qr_code))
          <img src="{{ asset('storage/'.$equipement->qr_code) }}?v={{ time() }}"
              alt="QR Code {{ $equipement->code_inventaire }}"
              class="img-fluid" style="max-width:180px">
        @else
          <div class="empty-state py-4">
            <i class="bi bi-qr-code" style="font-size:36px"></i>
            <p class="mt-2" style="font-size:13px">QR code non généré</p>
            <!-- Bouton pour régénérer manuellement -->
            <form method="POST" action="{{ route('equipements.regenerer-qr', $equipement) }}">
              @csrf
              <button class="btn btn-sm btn-outline-primary">
                <i class="bi bi-arrow-clockwise me-1"></i>Générer le QR
              </button>
            </form>
          </div>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection