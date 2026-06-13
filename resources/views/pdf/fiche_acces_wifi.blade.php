<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<style>
  body { font-family: DejaVu Sans, sans-serif; font-size:12px; }
  .header { border-bottom:3px solid #1a2035; padding-bottom:12px; margin-bottom:20px; }
  h1 { color:#1a2035; font-size:16px; margin:0 0 4px; }
  .meta { color:#6b7280; font-size:10px; }
  table { width:100%; border-collapse:collapse; margin-top:12px; }
  thead th { background:#1a2035; color:#fff; padding:8px; font-size:10px; }
  tbody tr:nth-child(even) { background:#f8f9fa; }
  tbody td { padding:8px; border-bottom:1px solid #e5e7eb; }
  .op-orange { background:#fff7ed; color:#c2410c; font-weight:bold; padding:2px 8px; border-radius:3px; }
  .op-mtn { background:#fefce8; color:#a16207; font-weight:bold; padding:2px 8px; border-radius:3px; }
  .pwd { font-family:monospace; background:#f4f6f9; padding:2px 6px; border-radius:3px; font-size:11px; }
  .footer { margin-top:20px; font-size:9px; color:#9ca3af; text-align:center; border-top:1px solid #e5e7eb; padding-top:8px; }
  .warning { background:#fef9c3; border:1px solid #fde68a; padding:8px 12px; border-radius:4px; font-size:10px; color:#78350f; margin-bottom:16px; }
</style>
</head>
<body>
<div class="header">
  <h1>FICHE DES ACCÈS INTERNET — {{ strtoupper($site->nom) }}</h1>
  <p class="meta">{{ $site->ville }}{{ $site->adresse ? ' · '.$site->adresse : '' }} · Généré le {{ now()->format('d/m/Y') }}</p>
</div>

<div class="warning">
  ⚠️ Document confidentiel — Ne pas divulguer les mots de passe à des tiers non autorisés.
</div>

<table>
  <thead>
    <tr>
      <th>Opérateur</th>
      <th>Nom BOX</th>
      <th>SSID (Réseau)</th>
      <th>Mot de passe</th>
      <th>Adresse IP</th>
      <th>MAC</th>
      <th>Statut</th>
      <th>Installation</th>
    </tr>
  </thead>
  <tbody>
    @forelse($accesWifis as $wifi)
    <tr>
      <td>
        <span class="op-{{ strtolower($wifi->operateur) }}">{{ $wifi->operateur }}</span>
      </td>
      <td><strong>{{ $wifi->nom_box }}</strong></td>
      <td>{{ $wifi->ssid }}</td>
      <td><span class="pwd">{{ $wifi->mot_de_passe }}</span></td>
      <td>{{ $wifi->adresse_ip ?? '—' }}</td>
      <td style="font-size:10px">{{ $wifi->adresse_mac ?? '—' }}</td>
      <td>{{ ucfirst($wifi->statut) }}</td>
      <td>{{ $wifi->date_installation?->format('d/m/Y') ?? '—' }}</td>
    </tr>
    @empty
    <tr><td colspan="8" style="text-align:center;color:#9ca3af;padding:20px">Aucun accès WIFI enregistré pour ce site</td></tr>
    @endforelse
  </tbody>
</table>

<div class="footer">
  ITSM Manager · Fiche accès internet · {{ $site->nom }} · {{ now()->format('d/m/Y H:i') }}
</div>
</body>
</html>