@extends('layouts.app')
@section('title', 'Modifier le système d\'alarme')
@section('content')

<div class="row justify-content-center">
  <div class="col-md-7">
    <div class="card">
      <div class="card-header fw-semibold">
        <i class="bi bi-pencil me-2"></i>Modifier — {{ $alarme->type_systeme }}
        <span class="badge bg-light text-dark border ms-2">{{ $alarme->site->nom }}</span>
      </div>
      <div class="card-body">
        <form method="POST" action="{{ route('alarmes.update', $alarme) }}">
          @csrf @method('PUT')
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label fw-medium">Site <span class="text-danger">*</span></label>
              <select name="site_id" class="form-select @error('site_id') is-invalid @enderror" required>
                @foreach($sites as $s)
                  <option value="{{ $s->id }}"
                    {{ old('site_id', $alarme->site_id) == $s->id ? 'selected' : '' }}>
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
                     value="{{ old('type_systeme', $alarme->type_systeme) }}"
                     placeholder="Ex: Intrusion, Incendie, Périmétrique..." required>
              @error('type_systeme')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
              <label class="form-label fw-medium">Marque</label>
              <input type="text" name="marque" class="form-control"
                     value="{{ old('marque', $alarme->marque) }}">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-medium">Zone couverte <span class="text-danger">*</span></label>
              <input type="text" name="zone_couverte"
                     class="form-control @error('zone_couverte') is-invalid @enderror"
                     value="{{ old('zone_couverte', $alarme->zone_couverte) }}"
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
                       value="{{ old('code_acces', $alarme->code_acces) }}">
                <button type="button" class="btn btn-outline-secondary"
                        onclick="togglePwd('code_acces', this)">
                  <i class="bi bi-eye"></i>
                </button>
              </div>
            </div>
            <div class="col-md-3">
              <label class="form-label fw-medium">Date d'installation</label>
              <input type="date" name="date_installation" class="form-control"
                     value="{{ old('date_installation', $alarme->date_installation?->format('Y-m-d')) }}">
            </div>
            <div class="col-md-3">
              <label class="form-label fw-medium">Statut <span class="text-danger">*</span></label>
              <select name="statut" class="form-select" required>
                @foreach(['actif','inactif','en_panne'] as $s)
                  <option value="{{ $s }}"
                    {{ old('statut', $alarme->statut) === $s ? 'selected' : '' }}>
                    {{ ucfirst(str_replace('_',' ',$s)) }}
                  </option>
                @endforeach
              </select>
            </div>
          </div>
          <hr>
          <div class="d-flex gap-2 justify-content-end">
            <a href="{{ route('alarmes.index') }}" class="btn btn-outline-secondary">Annuler</a>
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