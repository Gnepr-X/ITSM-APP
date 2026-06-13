<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<style>
  body { font-family: DejaVu Sans, sans-serif; font-size:12px; }
  .header { text-align:center; border-bottom:3px solid #1a2035; padding-bottom:12px; margin-bottom:20px; }
  h1 { color:#1a2035; font-size:17px; }
  .badge-fiche { background:#1a2035; color:#fff; padding:4px 14px; border-radius:4px; font-size:13px; }
  .section-title { background:#f1f5f9; padding:6px 10px; font-weight:bold; color:#1a2035; margin-top:18px; }
  table { width:100%; border-collapse:collapse; margin-top:8px; }
  td { padding:7px; border-bottom:1px solid #e5e7eb; }
  td:first-child { color:#6b7280; width:35%; }
  .etat-box { display:inline-block; padding:4px 12px; border-radius:4px; font-weight:bold; }
  .etat-bon { background:#d1fae5; color:#065f46; }
  .etat-acceptable { background:#dbeafe; color:#1e40af; }
  .etat-endommagé { background:#fef3c7; color:#92400e; }
  .etat-hors_service { background:#fee2e2; color:#991b1b; }
  .signatures { display:table; width:100%; margin-top:40px; }
  .sig { display:table-cell; width:48%; text-align:center; border-top:1px solid #333; padding-top:8px; }
</style>
</head>
<body>
<div class="header">
  <h1>FICHE DE RESTITUTION DE MATÉRIEL</h1>
  <span class="badge-fiche">Réf. {{ $attribution->numero_fiche }}</span>
</div>

<div class="section-title">MATÉRIEL RESTITUÉ</div>
<table>
  <tr><td>Code inventaire</td><td><strong>{{ $attribution->equipement->code_inventaire }}</strong></td></tr>
  <tr><td>Désignation</td><td>{{ $attribution->equipement->designation }}</td></tr>
  <tr><td>Marque / Modèle</td><td>{{ $attribution->equipement->marque }} {{ $attribution->equipement->modele }}</td></tr>
  <tr><td>N° de série</td><td>{{ $attribution->equipement->numero_serie ?? '—' }}</td></tr>
</table>

<div class="section-title">RESTITUÉ PAR</div>
<table>
  <tr><td>Nom & Prénom</td><td><strong>{{ $attribution->ressource->nom_complet }}</strong></td></tr>
  <tr><td>Matricule</td><td>{{ $attribution->ressource->matricule }}</td></tr>
  <tr><td>Date d'attribution</td><td>{{ $attribution->date_attribution->format('d/m/Y') }}</td></tr>
</table>

@if($attribution->restitution)
<div class="section-title">DÉTAILS DE LA RESTITUTION</div>
<table>
  <tr><td>Date de restitution</td><td><strong>{{ $attribution->restitution->date_restitution->format('d/m/Y') }}</strong></td></tr>
  <tr><td>Reçu par</td><td>{{ $attribution->restitution->recu_par }}</td></tr>
  <tr>
    <td>État de retour</td>
    <td>
      <span class="etat-box etat-{{ $attribution->restitution->etat_retour }}">
        {{ ucfirst($attribution->restitution->etat_retour) }}
      </span>
    </td>
  </tr>
  @if($attribution->restitution->observation)
    <tr><td>Observations</td><td>{{ $attribution->restitution->observation }}</td></tr>
  @endif
</table>
@endif

<div class="signatures">
  <div class="sig" style="margin-right:4%">
    <p>Restitué par</p>
    <br>
    <p><strong>{{ $attribution->ressource->nom_complet }}</strong></p>
  </div>
  <div class="sig" style="margin-left:4%">
    <p>Reçu par</p>
    <br>
    <p><strong>{{ $attribution->restitution?->recu_par }}</strong></p>
  </div>
</div>
</body>
</html>