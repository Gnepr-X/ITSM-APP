<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ $equipement->designation }} — Fiche technique</title>
  <style>
    * { margin:0; padding:0; box-sizing:border-box; }
    body {
      font-family: Arial, sans-serif;
      background:#f4f6f9;
      padding:20px;
      max-width:480px;
      margin:0 auto;
    }
    .top-banner {
      background:#1a2035;
      color:#fff;
      padding:16px 20px;
      border-radius:12px 12px 0 0;
      text-align:center;
    }
    .top-banner small { opacity:.7; font-size:11px; letter-spacing:1px; text-transform:uppercase; }
    .top-banner h1 { font-size:18px; margin-top:4px; }

    .card {
      background:#fff;
      border-radius:0 0 12px 12px;
      padding:20px;
      box-shadow:0 2px 10px rgba(0,0,0,.08);
      margin-bottom:16px;
    }

    .section {
      margin-top:20px;
    }
    .section-title {
      font-size:12px;
      text-transform:uppercase;
      letter-spacing:1px;
      color:#9ca3af;
      font-weight:bold;
      margin-bottom:10px;
      padding-bottom:6px;
      border-bottom:2px solid #f0f0f0;
    }

    .info-row {
      display:flex;
      justify-content:space-between;
      padding:8px 0;
      border-bottom:1px solid #f4f6f9;
      font-size:14px;
    }
    .info-row .label { color:#9ca3af; }
    .info-row .value { font-weight:600; color:#1a2035; text-align:right; }

    .statut-badge {
      display:inline-block;
      padding:4px 12px;
      border-radius:20px;
      font-size:12px;
      font-weight:bold;
    }
    .statut-disponible    { background:#d1fae5; color:#065f46; }
    .statut-attribue      { background:#dbeafe; color:#1e40af; }
    .statut-en_reparation { background:#fef3c7; color:#92400e; }
    .statut-hors_service  { background:#fee2e2; color:#991b1b; }

    .attribution-card {
      background:#eff6ff;
      border:1px solid #bfdbfe;
      border-radius:10px;
      padding:16px;
      margin-top:10px;
    }
    .attribution-card .icon-row {
      display:flex;
      align-items:center;
      gap:10px;
      margin-bottom:12px;
    }
    .attribution-card .avatar {
      width:40px; height:40px;
      background:#1a2035;
      color:#fff;
      border-radius:50%;
      display:flex;
      align-items:center;
      justify-content:center;
      font-weight:bold;
      font-size:16px;
    }
    .attribution-card .name { font-weight:700; font-size:15px; color:#1a2035; }
    .attribution-card .role { font-size:12px; color:#6b7280; }

    .no-attribution {
      text-align:center;
      padding:24px;
      color:#9ca3af;
      font-size:13px;
    }

    .footer-note {
      text-align:center;
      font-size:11px;
      color:#9ca3af;
      margin-top:20px;
      padding-top:12px;
    }
  </style>
</head>
<body>

  <div class="top-banner">
    <small>ITSM Manager — Fiche technique</small>
    <h1>{{ $equipement->designation }}</h1>
  </div>

  <div class="card">

    <!-- Statut -->
    <div style="text-align:center;margin-bottom:16px">
      <span class="statut-badge statut-{{ $equipement->statut }}">
        {{ ucfirst(str_replace('_',' ', $equipement->statut)) }}
      </span>
    </div>

    <!-- Infos matériel -->
    <div class="section">
      <div class="section-title">Informations matériel</div>
      <div class="info-row">
        <span class="label">Code inventaire</span>
        <span class="value">{{ $equipement->code_inventaire }}</span>
      </div>
      <div class="info-row">
        <span class="label">Type</span>
        <span class="value">{{ ucfirst($equipement->type) }}</span>
      </div>
      <div class="info-row">
        <span class="label">Marque</span>
        <span class="value">{{ $equipement->marque ?? '—' }}</span>
      </div>
      <div class="info-row">
        <span class="label">Modèle</span>
        <span class="value">{{ $equipement->modele ?? '—' }}</span>
      </div>
      <div class="info-row">
        <span class="label">N° de série</span>
        <span class="value">{{ $equipement->numero_serie ?? '—' }}</span>
      </div>
      <div class="info-row">
        <span class="label">Site</span>
        <span class="value">{{ $equipement->site->nom }}</span>
      </div>
      @if($equipement->date_acquisition)
      <div class="info-row">
        <span class="label">Acquisition</span>
        <span class="value">{{ $equipement->date_acquisition->format('d/m/Y') }}</span>
      </div>
      @endif
    </div>

    <!-- Ressource attributaire -->
    <div class="section">
      <div class="section-title">Attribué à</div>

      @if($attribution && $attribution->ressource)
        <div class="attribution-card">
          <div class="icon-row">
            <div class="avatar">
              {{ strtoupper(substr($attribution->ressource->prenom,0,1)) }}{{ strtoupper(substr($attribution->ressource->nom,0,1)) }}
            </div>
            <div>
              <div class="name">{{ $attribution->ressource->prenom }} {{ $attribution->ressource->nom }}</div>
              <div class="role">{{ $attribution->ressource->poste }}</div>
            </div>
          </div>
          <div class="info-row" style="border:none;padding:4px 0">
            <span class="label">Matricule</span>
            <span class="value">{{ $attribution->ressource->matricule }}</span>
          </div>
          @if($attribution->ressource->departement)
          <div class="info-row" style="border:none;padding:4px 0">
            <span class="label">Département</span>
            <span class="value">{{ $attribution->ressource->departement }}</span>
          </div>
          @endif
          <div class="info-row" style="border:none;padding:4px 0">
            <span class="label">Depuis le</span>
            <span class="value">{{ $attribution->date_attribution->format('d/m/Y') }}</span>
          </div>
        </div>
      @else
        <div class="no-attribution">
          📦 Cet équipement n'est actuellement attribué à personne
        </div>
      @endif
    </div>

  </div>

  <div class="footer-note">
    Document généré automatiquement · ITSM Manager
  </div>

</body>
</html>