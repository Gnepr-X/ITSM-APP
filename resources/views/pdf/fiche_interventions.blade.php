<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<style>
  body { font-family: DejaVu Sans, sans-serif; font-size:10px; }
  .header { border-bottom:3px solid #1a2035; padding-bottom:10px; margin-bottom:14px; }
  h1 { color:#1a2035; font-size:14px; margin:0; }
  .meta { color:#6b7280; font-size:9px; margin-top:3px; }
  table { width:100%; border-collapse:collapse; }
  thead th { background:#1a2035; color:#fff; padding:6px 7px; font-size:9px; text-align:left; }
  tbody tr:nth-child(even) { background:#f8f9fa; }
  tbody td { padding:5px 7px; border-bottom:1px solid #e5e7eb; }
  .p-critique { background:#fee2e2; color:#991b1b; padding:2px 5px; border-radius:2px; font-size:8px; font-weight:bold; }
  .p-haute    { background:#fef3c7; color:#92400e; padding:2px 5px; border-radius:2px; font-size:8px; }
  .p-normale  { background:#dbeafe; color:#1e40af; padding:2px 5px; border-radius:2px; font-size:8px; }
  .p-basse    { background:#f3f4f6; color:#374151; padding:2px 5px; border-radius:2px; font-size:8px; }
  .s-ouvert   { color:#dc2626; font-weight:bold; }
  .s-en_cours { color:#d97706; font-weight:bold; }
  .s-resolu   { color:#16a34a; }
  .s-ferme    { color:#6b7280; }
  .footer { margin-top:14px; font-size:8px; color:#9ca3af; text-align:center; border-top:1px solid #e5e7eb; padding-top:6px; }
</style>
</head>
<body>
<div class="header">
  <h1>RAPPORT DES INTERVENTIONS</h1>
  <p class="meta">Généré le {{ now()->format('d/m/Y à H:i') }} · {{ $interventions->count() }} intervention(s)</p>
</div>

<table>
  <thead>
    <tr>
      <th>N° Ticket</th>
      <th>Titre</th>
      <th>Type</th>
      <th>Site</th>
      <th>Ressource</th>
      <th>Priorité</th>
      <th>Statut</th>
      <th>Ouverture</th>
      <th>Résolution</th>
      <th>Durée</th>
    </tr>
  </thead>
  <tbody>
    @forelse($interventions as $i)
    <tr>
      <td><strong>{{ $i->numero_ticket }}</strong></td>
      <td>{{ Str::limit($i->titre, 35) }}</td>
      <td>{{ ucfirst($i->type) }}</td>
      <td>{{ $i->site->nom }}</td>
      <td>{{ $i->ressource?->nom_complet ?? '—' }}</td>
      <td><span class="p-{{ $i->priorite }}">{{ ucfirst($i->priorite) }}</span></td>
      <td><span class="s-{{ $i->statut }}">{{ ucfirst($i->statut) }}</span></td>
      <td>{{ $i->date_ouverture->format('d/m/Y') }}</td>
      <td>{{ $i->date_resolution?->format('d/m/Y') ?? '—' }}</td>
      <td>{{ $i->duree ?? '—' }}</td>
    </tr>
    @empty
    <tr><td colspan="10" style="text-align:center;padding:16px;color:#9ca3af">Aucune intervention</td></tr>
    @endforelse
  </tbody>
</table>

<div class="footer">
  ITSM Manager · Rapport interventions · {{ now()->format('d/m/Y H:i') }}
</div>
</body>
</html>
