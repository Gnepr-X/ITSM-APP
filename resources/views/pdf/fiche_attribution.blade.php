<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<style>
  * { margin:0; padding:0; box-sizing:border-box; }
  body { font-family: DejaVu Sans, sans-serif; font-size:12px; color:#1a1a1a; padding:20px; }

  .header { width:100%; margin-bottom:20px; padding-bottom:12px;
            border-bottom:3px solid #1a2035; }
  .header h1 { font-size:16px; color:#1a2035; margin-bottom:6px; }
  .header-meta { font-size:10px; color:#6b7280; }

  .badge-fiche { background:#1a2035; color:#fff; padding:4px 14px;
                 border-radius:4px; font-size:13px; display:inline-block; margin-bottom:6px; }

  .section-title { background:#e8eaf6; padding:6px 10px; font-weight:bold;
                   color:#1a2035; margin-top:18px; margin-bottom:6px;
                   font-size:11px; border-left:4px solid #1a2035; }

  table { width:100%; border-collapse:collapse; margin-top:4px; }
  table.info td { padding:6px 8px; border-bottom:1px solid #e5e7eb; }
  table.info td:first-child { color:#6b7280; width:35%; font-size:11px; }
  table.info td:last-child { font-weight:500; }

  table.materiel thead th { background:#1a2035; color:#fff;
                             padding:7px 8px; font-size:10px; text-align:left; }
  table.materiel tbody td { padding:7px 8px; border-bottom:1px solid #e5e7eb; font-size:11px; }
  table.materiel tbody tr:nth-child(even) { background:#f8f9fa; }

  .qr-block { float:right; text-align:center; margin-left:20px; }
  .qr-block img { width:90px; height:90px; }
  .qr-block p { font-size:9px; color:#9ca3af; margin-top:4px; }

  .observation-box { background:#f8f9fa; border:1px solid #e5e7eb;
                     padding:10px; border-radius:4px; font-size:12px;
                     margin-top:6px; }

  .signatures { width:100%; margin-top:50px; }
  .sig-left  { width:45%; display:inline-block; text-align:center;
               border-top:1px solid #333; padding-top:8px; vertical-align:top; }
  .sig-right { width:45%; display:inline-block; text-align:center;
               border-top:1px solid #333; padding-top:8px;
               margin-left:9%; vertical-align:top; }
  .sig-label { font-size:11px; color:#6b7280; margin-bottom:6px; }
  .sig-name  { font-size:12px; font-weight:bold; }

  .footer { margin-top:30px; padding-top:10px; border-top:1px solid #e5e7eb;
            font-size:9px; color:#9ca3af; text-align:center; }

  .clearfix::after { content:''; display:table; clear:both; }
</style>
</head>
<body>

<!-- En-tête -->
<div class="header clearfix">
  @if($attribution->equipement->qr_code && file_exists(storage_path('app/public/'.$attribution->equipement->qr_code)))
  <div class="qr-block">
    <img src="{{ storage_path('app/public/'.$attribution->equipement->qr_code) }}"
         alt="QR Code">
    <p>Scanner pour<br>infos techniques</p>
  </div>
  @endif
  <h1>FICHE D'ATTRIBUTION DE MATÉRIEL INFORMATIQUE</h1>
  <div class="badge-fiche">N° {{ $attribution->numero_fiche }}</div>
  <div class="header-meta">
    Date d'attribution : {{ \Carbon\Carbon::parse($attribution->date_attribution)->format('d/m/Y') }}
    &nbsp;·&nbsp; Attribué par : {{ $attribution->attribue_par }}
    &nbsp;·&nbsp; Site : {{ $attribution->site->nom }} — {{ $attribution->site->ville }}
  </div>
</div>

<!-- Bénéficiaire -->
<div class="section-title">BÉNÉFICIAIRE</div>
<table class="info">
  <tr>
    <td>Nom & Prénom</td>
    <td>{{ $attribution->ressource->prenom }} {{ $attribution->ressource->nom }}</td>
  </tr>
  <tr>
    <td>Matricule</td>
    <td>{{ $attribution->ressource->matricule }}</td>
  </tr>
  <tr>
    <td>Poste</td>
    <td>{{ $attribution->ressource->poste }}</td>
  </tr>
  @if($attribution->ressource->departement)
  <tr>
    <td>Département</td>
    <td>{{ $attribution->ressource->departement }}</td>
  </tr>
  @endif
  @if($attribution->ressource->email)
  <tr>
    <td>Email</td>
    <td>{{ $attribution->ressource->email }}</td>
  </tr>
  @endif
  @if($attribution->ressource->telephone)
  <tr>
    <td>Téléphone</td>
    <td>{{ $attribution->ressource->telephone }}</td>
  </tr>
  @endif
</table>

<!-- Matériel -->
<div class="section-title">MATÉRIEL ATTRIBUÉ</div>
<table class="materiel">
  <thead>
    <tr>
      <th>Code inventaire</th>
      <th>Désignation</th>
      <th>Type</th>
      <th>Marque / Modèle</th>
      <th>N° Série</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td><strong>{{ $attribution->equipement->code_inventaire }}</strong></td>
      <td>{{ $attribution->equipement->designation }}</td>
      <td>{{ ucfirst($attribution->equipement->type) }}</td>
      <td>{{ $attribution->equipement->marque }} {{ $attribution->equipement->modele }}</td>
      <td>{{ $attribution->equipement->numero_serie ?? '—' }}</td>
    </tr>
  </tbody>
</table>

<!-- Observations -->
@if($attribution->observation)
<div class="section-title">OBSERVATIONS</div>
<div class="observation-box">{{ $attribution->observation }}</div>
@endif

<!-- Signatures -->
<div class="signatures">
  <div class="sig-left">
    <div class="sig-label">Remis par</div>
    <br><br>
    <div class="sig-name">{{ $attribution->attribue_par }}</div>
  </div>
  <div class="sig-right">
    <div class="sig-label">Reçu par le bénéficiaire</div>
    <br><br>
    <div class="sig-name">
      {{ $attribution->ressource->prenom }} {{ $attribution->ressource->nom }}
    </div>
  </div>
</div>

<!-- Pied de page -->
<div class="footer">
  ITSM Manager &nbsp;·&nbsp; Fiche d'attribution &nbsp;·&nbsp;
  Générée le {{ now()->format('d/m/Y à H:i') }}
</div>

</body>
</html>