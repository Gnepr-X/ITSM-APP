<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<style>
  body { font-family: DejaVu Sans, sans-serif; font-size:11px; }
  .header { border-bottom:3px solid #1a2035; padding-bottom:10px; margin-bottom:16px; }
  h1 { color:#1a2035; font-size:15px; margin:0; }
  .meta { color:#6b7280; font-size:10px; margin-top:4px; }
  table { width:100%; border-collapse:collapse; margin-top:10px; }
  thead th { background:#1a2035; color:#fff; padding:7px 8px; font-size:10px; text-align:left; }
  tbody tr:nth-child(even) { background:#f8f9fa; }
  tbody td { padding:6px 8px; border-bottom:1px solid #e5e7eb; vertical-align:top; }
  .equip-list { font-size:9px; color:#6b7280; }
  .footer { margin-top:16px; font-size:9px; color:#9ca3af; text-align:center; border-top:1px solid #e5e7eb; padding-top:8px; }
</style>
</head>
<body>
<div class="header">
  <h1>FICHE DES RESSOURCES HUMAINES & ÉQUIPEMENTS ATTRIBUÉS</h1>
  <p class="meta">
    {{ $site ? $site->nom.' — '.$site->ville : 'Tous les sites' }} · Généré le {{ now()->format('d/m/Y') }}
    · {{ $ressources->count() }} ressource(s)
  </p>
</div>

<table>
  <thead>
    <tr>
      <th>Matricule</th>
      <th>Nom & Prénom</th>
      <th>Poste / Département</th>
      <th>Site</th>
      <th>Contact</th>
      <th>Équipements attribués</th>
    </tr>
  </thead>
  <tbody>
    @forelse($ressources as $r)
    <tr>
      <td><strong>{{ $r->matricule }}</strong></td>
      <td>{{ $r->nom_complet }}</td>
      <td>
        {{ $r->poste }}
        @if($r->departement)<br><span style="color:#6b7280">{{ $r->departement }}</span>@endif
      </td>
      <td>{{ $r->site->nom }}</td>
      <td style="font-size:10px">
        {{ $r->email ?? '' }}
        @if($r->telephone)<br>{{ $r->telephone }}@endif
      </td>
      <td>
        @php $actifs = $r->attributions->where('statut','actif'); @endphp
        @forelse($actifs as $a)
          <div class="equip-list">• {{ $a->equipement->code_inventaire }} — {{ $a->equipement->designation }}</div>
        @empty
          <span style="color:#9ca3af">—</span>
        @endforelse
      </td>
    </tr>
    @empty
    <tr><td colspan="6" style="text-align:center;padding:20px;color:#9ca3af">Aucune ressource</td></tr>
    @endforelse
  </tbody>
</table>

<div class="footer">
  ITSM Manager · Fiche ressources · {{ now()->format('d/m/Y H:i') }}
</div>
</body>
</html>