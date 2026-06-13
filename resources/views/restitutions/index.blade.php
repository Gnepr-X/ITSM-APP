@extends('layouts.app')
@section('title', 'Restitutions')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
  <div>
    <h5 class="fw-bold mb-1">Restitutions de matériel</h5>
    <small class="text-muted">{{ $restitutions->total() }} restitutions</small>
  </div>
  <a href="{{ route('restitutions.create') }}" class="btn btn-primary btn-sm">
    <i class="bi bi-plus-lg me-1"></i>Nouvelle restitution
  </a>
</div>

<div class="card">
  <div class="card-body p-0">
    <table class="table table-hover mb-0">
      <thead>
        <tr>
          <th>N° Fiche</th>
          <th>Équipement</th>
          <th>Restitué par</th>
          <th>Site</th>
          <th>Date restitution</th>
          <th>État retour</th>
          <th>Reçu par</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($restitutions as $r)
        @php
          $etats = [
            'bon'         => 'success',
            'acceptable'  => 'primary',
            'endommagé'   => 'warning',
            'hors_service'=> 'danger',
          ];
        @endphp
        <tr>
          <td>
            <code style="font-size:11px">{{ $r->attribution->numero_fiche }}</code>
          </td>
          <td>
            <span style="font-weight:500">
              {{ $r->attribution->equipement->designation }}
            </span>
            <br>
            <small class="text-muted">
              <code style="font-size:10px">{{ $r->attribution->equipement->code_inventaire }}</code>
            </small>
          </td>
          <td>
            <span style="font-weight:500">{{ $r->attribution->ressource->nom_complet }}</span>
            <br>
            <small class="text-muted">{{ $r->attribution->ressource->matricule }}</small>
          </td>
          <td>
            <span class="badge bg-light text-dark border" style="font-size:11px">
              {{ $r->attribution->site->nom }}
            </span>
          </td>
          <td style="font-size:13px">
            {{ $r->date_restitution->format('d/m/Y') }}
          </td>
          <td>
            <span class="badge bg-{{ $etats[$r->etat_retour] ?? 'secondary' }}">
              {{ ucfirst($r->etat_retour) }}
            </span>
          </td>
          <td style="font-size:13px">{{ $r->recu_par }}</td>
          <td>
            <div class="d-flex gap-1">
              <a href="{{ route('pdf.restitution', $r->attribution) }}" target="_blank"
                 class="btn btn-sm btn-outline-danger btn-action" title="Fiche PDF">
                <i class="bi bi-file-pdf"></i>
              </a>
              <a href="{{ route('restitutions.show', $r) }}"
                 class="btn btn-sm btn-outline-primary btn-action">
                <i class="bi bi-eye"></i>
              </a>
              <form method="POST" action="{{ route('restitutions.destroy', $r) }}"
                    onsubmit="return confirm('Annuler cette restitution ? L\'équipement redeviendra attribué.')">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-outline-warning btn-action" title="Annuler la restitution">
                  <i class="bi bi-arrow-counterclockwise"></i>
                </button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="8">
            <div class="empty-state">
              <i class="bi bi-box-arrow-left"></i>
              <p>Aucune restitution enregistrée</p>
            </div>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
<div class="mt-3">{{ $restitutions->links() }}</div>
@endsection