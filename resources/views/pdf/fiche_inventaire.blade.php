<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<style>
  body { font-family: DejaVu Sans, sans-serif; font-size:11px; color:#1a1a1a; }
  .header { display:table; width:100%; border-bottom:3px solid #1a2035; padding-bottom:10px; margin-bottom:16px; }
  .header-left { display:table-cell; vertical-align:middle; }
  .header-right { display:table-cell; text-align:right; vertical-align:middle; }
  h1 { color:#1a2035; font-size:16px; margin:0 0 4px; }
  .meta { color:#6b7280; font-size:10px; }
  table { width:100%; border-collapse:collapse; margin-top:10px; }
  thead th { background:#1a2035; color:#fff; padding:7px 8px; text-align:left; font-size:10px; }
  tbody tr:nth-child(even) { background:#f8f9fa; }
  tbody td { padding:6px 8px; border-bottom:1px solid #e5e7eb; }
  .badge { padding:2px 6px; border-radius:3px; font-size:9px; font-weight:bold; }
  .badge-disponible { background:#d1fae5; color:#065f46; }
  .badge-attribue { background:#dbeafe; color:#1e40af; }
  .badge-en_reparation { background:#fef3c7; color:#92400e; }
  .badge-hors_service { background:#fee2e2; color:#991b1b; }
  .footer { margin-top:20px; padding-top:10px; border-top:1px solid #e5e7eb; font-size:9px; color:#9ca3af; text-align:center; }
  .section-title { background:#e8eaf6; padding:5px 8px; font-weight:bold; color:#1a2035; margin-top:16px; margin-bottom:4px; font-size:11px; }
</style>
</head>
<body>

<div class="header">
  <div class="header-left">
    <h1>FICHE D'INVENTAIRE DES ÉQUIPEMENTS</h1>
    <p class="meta">
      Généré le {{ now()->format('d/m/Y à H:i') }}
      @if($site) — Site : {{ $site->nom }} ({{ $site->ville }}) @else — Tous les sites @endif
    </p>
  </div>
  <div class="header-right">
    <div style="font-size:10px;color:#6b7280">ITSM Manager</div>
    <div style="font-size:18px;font-weight:700;color:#1a2035">{{ $equipements->count() }}</div>
    <div style="font-size:10px;color:#6b7280">équipements</div>
  </div>
</div>

@php $bySite = $equipements->groupBy(fn($e) => $e->site->nom); @endphp

@foreach($bySite as $siteName => $items)
<div class="section-title">📍 {{ $siteName }} — {{ $items->count() }} équipement(s)</div>
<table>
  <thead>
    <tr>
      <th>Code</th>
      <th>Désignation</th>
      <th>Type</th>
      <th>Marque / Modèle</th>
      <th>N° Série</th>
      <th>Statut</th>
      <th>Attribué à</th>
      <th>Acquisition</th>
    </tr>
  </thead>
  <tbody>
    @foreach($items as $eq)
    <tr>
      <td><strong>{{ $eq->code_inventaire }}</strong></td>
      <td>{{ $eq->designation }}</td>
      <td>{{ ucfirst($eq->type) }}</td>
      <td>{{ $eq->marque }} {{ $eq->modele }}</td>
      <td style="font-size:9px">{{ $eq->numero_serie ?? '—' }}</td>
      <td>
        <span class="badge badge-{{ $eq->statut }}">
          {{ ucfirst(str_replace('_',' ',$eq->statut)) }}
        </span>
      </td>
      <td style="font-size:9px">
        @php $attr = $eq->attributions->firstWhere('statut','actif'); @endphp
        {{ $attr ? $attr->ressource->nom_complet : '—' }}
      </td>
      <td>{{ $eq->date_acquisition?->format('d/m/Y') ?? '—' }}</td>
    </tr>
    @endforeach
  </tbody>
</table>
@endforeach

<div class="footer">
  ITSM Manager · Inventaire généré automatiquement · {{ now()->format('d/m/Y H:i') }}
</div>
</body>
</html>