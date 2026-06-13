@extends('layouts.app')
@section('title', 'Nouvelle BOX WIFI')
@section('content')

<div class="row justify-content-center">
  <div class="col-md-8">
    <div class="card">
      <div class="card-header fw-semibold">
        <i class="bi bi-wifi me-2"></i>Nouvelle BOX internet
      </div>
      <div class="card-body">
        <form method="POST" action="{{ route('acces-wifis.store') }}">
          @csrf
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label fw-medium">Site <span class="text-danger">*</span></label>
              <select name="site_id"
                      class="form-select @error('site_id') is-invalid @enderror" required>
                <option value="">Sélectionner un site...</option>
                @foreach($sites as $s)
                  <option value="{{ $s->id }}"
                    {{ old('site_id') == $s->id ? 'selected' : '' }}>
                    {{ $s->nom }} — {{ $s->ville }}
                  </option>
                @endforeach
              </select>
              @error('site_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-3">
              <label class="form-label fw-medium">Opérateur <span class="text-danger">*</span></label>
              <select name="operateur" class="form-select" required>
                <option value="">Choisir...</option>
                <option value="ORANGE" {{ old('operateur') === 'ORANGE' ? 'selected' : '' }}>
                  🟠 ORANGE
                </option>
                <option value="MTN"    {{ old('operateur') === 'MTN'    ? 'selected' : '' }}>
                  🟡 MTN
                </option>
                <option value="AUTRE"  {{ old('operateur') === 'AUTRE'  ? 'selected' : '' }}>
                  Autre
                </option>
              </select>
            </div>
            <div class="col-md-3">
              <label class="form-label fw-medium">Statut <span class="text-danger">*</span></label>
              <select name="statut" class="form-select" required>
                <option value="actif"   {{ old('statut','actif') === 'actif'   ? 'selected' : '' }}>Actif</option>
                <option value="inactif" {{ old('statut') === 'inactif' ? 'selected' : '' }}>Inactif</option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-medium">Nom de la BOX <span class="text-danger">*</span></label>
              <input type="text" name="nom_box"
                     class="form-control @error('nom_box') is-invalid @enderror"
                     value="{{ old('nom_box') }}"
                     placeholder="Ex: BOX-ORANGE-SIEGE-01" required>
              @error('nom_box')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
              <label class="form-label fw-medium">SSID (Nom du réseau) <span class="text-danger">*</span></label>
              <input type="text" name="ssid"
                     class="form-control @error('ssid') is-invalid @enderror"
                     value="{{ old('ssid') }}"
                     placeholder="Ex: ENTREPRISE_WIFI" required>
              @error('ssid')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
              <label class="form-label fw-medium">Mot de passe <span class="text-danger">*</span></label>
              <div class="input-group">
                <input type="password" name="mot_de_passe" id="mot_de_passe"
                       class="form-control @error('mot_de_passe') is-invalid @enderror"
                       value="{{ old('mot_de_passe') }}" required>
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
                     value="{{ old('date_installation') }}">
            </div>
            <div class="col-md-4">
              <label class="form-label fw-medium">Adresse IP</label>
              <input type="text" name="adresse_ip"
                     class="form-control @error('adresse_ip') is-invalid @enderror"
                     value="{{ old('adresse_ip') }}"
                     placeholder="Ex: 192.168.1.1">
              @error('adresse_ip')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
              <label class="form-label fw-medium">Adresse MAC</label>
              <input type="text" name="adresse_mac" class="form-control"
                     value="{{ old('adresse_mac') }}"
                     placeholder="Ex: AA:BB:CC:DD:EE:FF">
            </div>
            <div class="col-12">
              <label class="form-label fw-medium">Notes</label>
              <textarea name="notes" class="form-control" rows="2"
                        placeholder="Observations, restrictions d'accès, contrat...">{{ old('notes') }}</textarea>
            </div>
          </div>
          <hr>
          <div class="alert alert-warning d-flex align-items-center gap-2" style="font-size:13px">
            <i class="bi bi-shield-lock-fill"></i>
            Les mots de passe sont stockés de façon sécurisée et visibles uniquement dans les fiches PDF confidentielles.
          </div>
          <div class="d-flex gap-2 justify-content-end">
            <a href="{{ route('acces-wifis.index') }}" class="btn btn-outline-secondary">Annuler</a>
            <button type="submit" class="btn btn-primary">
              <i class="bi bi-check-lg me-1"></i>Enregistrer
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