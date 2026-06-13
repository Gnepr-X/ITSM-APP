@extends('layouts.app')
@section('title', 'Nouveau système d\'alarme')
@section('content')

<div class="row justify-content-center">
  <div class="col-md-7">
    <div class="card">
      <div class="card-header fw-semibold">
        <i class="bi bi-shield-fill-check me-2"></i>Nouveau système d'alarme
      </div>
      <div class="card-body">
        <form method="POST" action="{{ route('alarmes.store') }}">
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
            <div class="col-md-6">
              <label class="form-label fw-medium">Type de système <span class="text-danger">*</span></label>
              <input type="text" name="type_systeme"
                     class="form-control @error('type_systeme') is-invalid @enderror"
                     value="{{ old('type_systeme') }}"
                     placeholder="Ex: Intrusion, Incendie, Périmétrique..." required>
              @error('type_systeme')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
              <label class="form-label fw-medium">Marque</label>
              <input type="text" name="marque" class="form-control"
                     value="{{ old('marque') }}"
                     placeholder="Ex: Paradox, DSC, Bosch...">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-medium">Zone couverte <span class="text-danger">*</span></label>
              <input type="text" name="zone_couverte"
                     class="form-control @error('zone_couverte') is-invalid @enderror"
                     value="{{ old('zone_couverte') }}"
                     placeholder="Ex: Bâtiment A, Entrepôt, Salle serveurs..." required>
              @error('zone_couverte')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
              <label class="form-label fw-medium">
                Code d'accès
                <span class="text-muted" style="font-size:11px">(admin uniquement)</span>
              </label>
              <div class="input-group">
                <input type="password" name="code_acces" id="code_acces"
                       class="form-control"
                       value="{{ old('code_acces') }}"
                       placeholder="Code PIN ou mot de passe">
                <button type="button" class="btn btn-outline-secondary"
                        onclick="togglePwd('code_acces', this)">
                  <i class="bi bi-eye"></i>
                </button>
              </div>
            </div>
            <div class="col-md-3">
              <label class="form-label fw-medium">Date d'installation</label>
              <input type="date" name="date_installation" class="form-control"
                     value="{{ old('date_installation') }}">
            </div>
            <div class="col-md-3">
              <label class="form-label fw-medium">Statut <span class="text-danger">*</span></label>
              <select name="statut" class="form-select" required>
                <option value="actif"    {{ old('statut','actif') === 'actif'    ? 'selected' : '' }}>Actif</option>
                <option value="inactif"  {{ old('statut') === 'inactif'  ? 'selected' : '' }}>Inactif</option>
                <option value="en_panne" {{ old('statut') === 'en_panne' ? 'selected' : '' }}>En panne</option>
              </select>
            </div>
          </div>
          <hr>
          <div class="alert alert-warning d-flex align-items-center gap-2" style="font-size:13px">
            <i class="bi bi-lock-fill"></i>
            Le code d'accès est chiffré et visible uniquement par les administrateurs.
          </div>
          <div class="d-flex gap-2 justify-content-end">
            <a href="{{ route('alarmes.index') }}" class="btn btn-outline-secondary">Annuler</a>
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