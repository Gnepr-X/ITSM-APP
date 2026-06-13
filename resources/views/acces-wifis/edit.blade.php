@extends('layouts.app')
@section('title', 'Modifier l\'accès WIFI')
@section('content')

<div class="row justify-content-center">
  <div class="col-md-8">
    <div class="card">
      <div class="card-header fw-semibold">
        <i class="bi bi-pencil me-2"></i>Modifier — {{ $accesWifi->nom_box }}
        <span class="badge ms-2 {{ $accesWifi->operateur === 'ORANGE' ? 'bg-warning text-dark' : 'bg-warning text-dark' }}">
          {{ $accesWifi->operateur }}
        </span>
      </div>
      <div class="card-body">
        <form method="POST" action="{{ route('acces-wifis.update', $accesWifi) }}">
          @csrf @method('PUT')
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label fw-medium">Site <span class="text-danger">*</span></label>
              <select name="site_id" class="form-select @error('site_id') is-invalid @enderror" required>
                @foreach($sites as $s)
                  <option value="{{ $s->id }}"
                    {{ old('site_id', $accesWifi->site_id) == $s->id ? 'selected' : '' }}>
                    {{ $s->nom }} — {{ $s->ville }}
                  </option>
                @endforeach
              </select>
              @error('site_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-3">
              <label class="form-label fw-medium">Opérateur <span class="text-danger">*</span></label>
              <select name="operateur" class="form-select" required>
                @foreach(['ORANGE','MTN','AUTRE'] as $op)
                  <option value="{{ $op }}"
                    {{ old('operateur', $accesWifi->operateur) === $op ? 'selected' : '' }}>
                    {{ $op }}
                  </option>
                @endforeach
              </select>
            </div>
            <div class="col-md-3">
              <label class="form-label fw-medium">Statut <span class="text-danger">*</span></label>
              <select name="statut" class="form-select" required>
                <option value="actif"   {{ old('statut', $accesWifi->statut) === 'actif'   ? 'selected' : '' }}>Actif</option>
                <option value="inactif" {{ old('statut', $accesWifi->statut) === 'inactif' ? 'selected' : '' }}>Inactif</option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-medium">Nom de la BOX <span class="text-danger">*</span></label>
              <input type="text" name="nom_box"
                     class="form-control @error('nom_box') is-invalid @enderror"
                     value="{{ old('nom_box', $accesWifi->nom_box) }}"
                     placeholder="Ex: BOX-ORANGE-SIEGE" required>
              @error('nom_box')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
              <label class="form-label fw-medium">SSID (Nom du réseau) <span class="text-danger">*</span></label>
              <input type="text" name="ssid"
                     class="form-control @error('ssid') is-invalid @enderror"
                     value="{{ old('ssid', $accesWifi->ssid) }}" required>
              @error('ssid')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
              <label class="form-label fw-medium">Mot de passe <span class="text-danger">*</span></label>
              <div class="input-group">
                <input type="password" name="mot_de_passe" id="mot_de_passe"
                       class="form-control @error('mot_de_passe') is-invalid @enderror"
                       value="{{ old('mot_de_passe', $accesWifi->mot_de_passe) }}" required>
                <button type="button" class="btn btn-outline-secondary"
                        onclick="togglePwd('mot_de_passe', this)">
                  <i class="bi bi-eye"></i>
                </button>
              </div>
              @error('mot_de_passe')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
              <label class="form-label fw-medium">Date d'installation</label>
              <input type="date" name="date_installation" class="form-control"
                     value="{{ old('date_installation', $accesWifi->date_installation?->format('Y-m-d')) }}">
            </div>
            <div class="col-md-4">
              <label class="form-label fw-medium">Adresse IP</label>
              <input type="text" name="adresse_ip"
                     class="form-control @error('adresse_ip') is-invalid @enderror"
                     value="{{ old('adresse_ip', $accesWifi->adresse_ip) }}"
                     placeholder="Ex: 192.168.1.1">
              @error('adresse_ip')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
              <label class="form-label fw-medium">Adresse MAC</label>
              <input type="text" name="adresse_mac" class="form-control"
                     value="{{ old('adresse_mac', $accesWifi->adresse_mac) }}"
                     placeholder="Ex: AA:BB:CC:DD:EE:FF">
            </div>
            <div class="col-12">
              <label class="form-label fw-medium">Notes</label>
              <textarea name="notes" class="form-control" rows="2"
                        placeholder="Observations, restrictions d'accès...">{{ old('notes', $accesWifi->notes) }}</textarea>
            </div>
          </div>
          <hr>
          <div class="d-flex gap-2 justify-content-end">
            <a href="{{ route('acces-wifis.index') }}" class="btn btn-outline-secondary">Annuler</a>
            <button type="submit" class="btn btn-primary">
              <i class="bi bi-check-lg me-1"></i>Mettre à jour
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
function togglePwd(id, btn) {
  const input = document.getElementById(id);
  if (input.type === 'password') {
    input.type = 'text';
    btn.innerHTML = '<i class="bi bi-eye-slash"></i>';
  } else {
    input.type = 'password';
    btn.innerHTML = '<i class="bi bi-eye"></i>';
  }
}
</script>
@endpush
@endsection