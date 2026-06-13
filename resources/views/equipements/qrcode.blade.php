<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>QR Code — {{ $equipement->code_inventaire }}</title>
  <style>
    * { margin:0; padding:0; box-sizing:border-box; }
    body {
      font-family: Arial, sans-serif;
      background:#f4f6f9;
      display:flex;
      flex-direction:column;
      align-items:center;
      justify-content:center;
      min-height:100vh;
      padding:20px;
    }

    .qr-card {
      background:#fff;
      border:2px solid #1a2035;
      border-radius:16px;
      padding:30px 40px;
      text-align:center;
      max-width:320px;
      width:100%;
      box-shadow:0 4px 20px rgba(0,0,0,.1);
    }

    .qr-card .brand {
      font-size:11px;
      color:#9ca3af;
      text-transform:uppercase;
      letter-spacing:2px;
      margin-bottom:12px;
    }

    .qr-card .designation {
      font-size:16px;
      font-weight:700;
      color:#1a2035;
      margin-bottom:4px;
    }

    .qr-card .type-badge {
      display:inline-block;
      background:#e8eaf6;
      color:#1a2035;
      font-size:11px;
      padding:3px 10px;
      border-radius:20px;
      margin-bottom:20px;
    }

    .qr-card .qr-wrapper {
      background:#f8f9fa;
      border-radius:12px;
      padding:16px;
      margin-bottom:16px;
      display:inline-block;
    }

    .qr-card .qr-wrapper img {
      width:200px;
      height:200px;
      display:block;
    }

    .qr-card .code {
      font-family: monospace;
      font-size:13px;
      background:#f4f6f9;
      color:#1a2035;
      padding:5px 14px;
      border-radius:6px;
      display:inline-block;
      margin-bottom:12px;
      border:1px solid #e5e7eb;
    }

    .qr-card .info-grid {
      width:100%;
      border-collapse:collapse;
      margin-bottom:6px;
      text-align:left;
    }

    .qr-card .info-grid td {
      padding:4px 6px;
      font-size:12px;
    }

    .qr-card .info-grid td:first-child {
      color:#9ca3af;
      width:45%;
    }

    .qr-card .info-grid td:last-child {
      font-weight:500;
      color:#1a2035;
    }

    .qr-card .scan-hint {
      font-size:10px;
      color:#9ca3af;
      margin-top:12px;
    }

    /* Statut badge */
    .statut-badge {
      display:inline-block;
      padding:2px 8px;
      border-radius:4px;
      font-size:11px;
      font-weight:600;
    }
    .statut-disponible   { background:#d1fae5; color:#065f46; }
    .statut-attribue     { background:#dbeafe; color:#1e40af; }
    .statut-en_reparation{ background:#fef3c7; color:#92400e; }
    .statut-hors_service { background:#fee2e2; color:#991b1b; }

    /* Boutons */
    .btn-group {
      display:flex;
      gap:10px;
      margin-top:20px;
      justify-content:center;
    }

    .btn {
      padding:8px 20px;
      border-radius:8px;
      border:none;
      cursor:pointer;
      font-size:13px;
      font-weight:500;
      text-decoration:none;
      display:inline-flex;
      align-items:center;
      gap:6px;
    }

    .btn-print {
      background:#1a2035;
      color:#fff;
    }

    .btn-back {
      background:#f4f6f9;
      color:#1a2035;
      border:1px solid #e5e7eb;
    }

    .btn-print:hover { background:#2d3a5e; }
    .btn-back:hover  { background:#e5e7eb; }

    /* Masquer les boutons à l'impression */
    @media print {
      body { background:#fff; }
      .btn-group { display:none; }
      .qr-card {
        box-shadow:none;
        border:2px solid #1a2035;
        border-radius:8px;
      }
    }
  </style>
</head>
<body>

  <div class="qr-card">

    <div class="brand">ITSM Manager</div>

    <div class="designation">{{ $equipement->designation }}</div>

    <div class="type-badge">{{ ucfirst($equipement->type) }}</div>

    <!-- QR Code -->
    <div>
      @if($equipement->qr_code && \Storage::disk('public')->exists($equipement->qr_code))
        <div class="qr-wrapper">
          <img src="{{ asset('storage/'.$equipement->qr_code) }}"
               alt="QR Code {{ $equipement->code_inventaire }}">
        </div>
      @else
        <div style="width:200px;height:200px;background:#f4f6f9;border-radius:12px;
                    display:flex;align-items:center;justify-content:center;
                    margin:0 auto 16px;color:#9ca3af;font-size:13px;text-align:center;
                    padding:20px;">
          QR code non disponible.<br>
          <small>Veuillez régénérer depuis la fiche équipement.</small>
        </div>
      @endif
    </div>

    <!-- Code inventaire -->
    <div class="code">{{ $equipement->code_inventaire }}</div>

    <!-- Infos techniques -->
    <table class="info-grid">
      <tr>
        <td>Marque / Modèle</td>
        <td>{{ $equipement->marque ?? '—' }} {{ $equipement->modele ?? '' }}</td>
      </tr>
      @if($equipement->numero_serie)
      <tr>
        <td>N° de série</td>
        <td style="font-family:monospace;font-size:11px">{{ $equipement->numero_serie }}</td>
      </tr>
      @endif
      <tr>
        <td>Site</td>
        <td>{{ $equipement->site->nom }}</td>
      </tr>
      <tr>
        <td>Ville</td>
        <td>{{ $equipement->site->ville }}</td>
      </tr>
      <tr>
        <td>Statut</td>
        <td>
          <span class="statut-badge statut-{{ $equipement->statut }}">
            {{ ucfirst(str_replace('_',' ',$equipement->statut)) }}
          </span>
        </td>
      </tr>
      @if($equipement->date_acquisition)
      <tr>
        <td>Acquisition</td>
        <td>{{ $equipement->date_acquisition->format('d/m/Y') }}</td>
      </tr>
      @endif
    </table>

    <div class="scan-hint">
      Scanner ce QR code pour afficher<br>les informations techniques complètes
    </div>

  </div>

  <!-- Boutons -->
  <div class="btn-group">
    <button onclick="window.print()" class="btn btn-print">
      🖨️ Imprimer
    </button>
    <a href="{{ route('equipements.show', $equipement) }}" class="btn btn-back">
      ← Retour
    </a>
  </div>

</body>
</html>