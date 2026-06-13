<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>IT Manager — @yield('title')</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body { background:#f4f6f9; }
    .sidebar { min-height:100vh; background:#1a2035; width:250px; position:fixed; top:0; left:0; z-index:100; overflow-y:auto; }
    .sidebar .brand { padding:20px 16px; border-bottom:1px solid rgba(255,255,255,.1); }
    .sidebar .brand h5 { color:#fff; margin:0; font-weight:700; font-size:16px; }
    .sidebar .brand small { color:rgba(255,255,255,.5); font-size:11px; }
    .sidebar .nav-section { padding:12px 16px 4px; color:rgba(255,255,255,.4); font-size:10px; text-transform:uppercase; letter-spacing:1px; }
    .sidebar .nav-link { color:rgba(255,255,255,.7); padding:9px 16px; border-radius:6px; margin:2px 8px; display:flex; align-items:center; gap:10px; font-size:13.5px; transition:.15s; }
    .sidebar .nav-link:hover, .sidebar .nav-link.active { background:rgba(255,255,255,.1); color:#fff; }
    .sidebar .nav-link i { font-size:16px; width:18px; }
    .sidebar .badge-count { margin-left:auto; font-size:10px; }
    .main-content { margin-left:250px; min-height:100vh; }
    .topbar { background:#fff; border-bottom:1px solid #e5e7eb; padding:12px 24px; display:flex; align-items:center; justify-content:space-between; position:sticky; top:0; z-index:99; }
    .topbar .page-title { font-size:16px; font-weight:600; color:#1a2035; margin:0; }
    .content-area { padding:24px; }
    .card { border:none; border-radius:12px; box-shadow:0 1px 4px rgba(0,0,0,.08); }
    .card-header { background:#fff; border-bottom:1px solid #f0f0f0; border-radius:12px 12px 0 0 !important; padding:16px 20px; }
    .stat-card { border-radius:12px; padding:20px; color:#fff; border:none; }
    .stat-card .stat-icon { font-size:32px; opacity:.8; }
    .stat-card .stat-value { font-size:28px; font-weight:700; }
    .stat-card .stat-label { font-size:13px; opacity:.85; }
    .table th { font-size:12px; text-transform:uppercase; letter-spacing:.5px; color:#6b7280; font-weight:600; border-bottom:2px solid #f0f0f0; }
    .table td { font-size:13.5px; vertical-align:middle; }
    .badge { font-size:11px; font-weight:500; padding:4px 8px; }
    .btn-action { padding:4px 10px; font-size:12px; }
    .filter-bar { background:#fff; border-radius:10px; padding:16px 20px; margin-bottom:20px; box-shadow:0 1px 4px rgba(0,0,0,.06); }
    .empty-state { text-align:center; padding:60px 20px; color:#9ca3af; }
    .empty-state i { font-size:48px; display:block; margin-bottom:16px; }
  </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
  <div class="brand">
    <h5><i class="bi bi-cpu-fill me-2"></i>ITSM Manager</h5>
    <small>Gestion IT Multi-sites</small>
  </div>

  <div class="nav-section">Principal</div>
  <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
    <i class="bi bi-grid-1x2-fill"></i> Tableau de bord
  </a>

  <div class="nav-section">Organisation</div>
  <a href="{{ route('sites.index') }}" class="nav-link {{ request()->routeIs('sites.*') ? 'active' : '' }}">
    <i class="bi bi-building"></i> Sites & Localités
  </a>
  <a href="{{ route('ressources.index') }}" class="nav-link {{ request()->routeIs('ressources.*') ? 'active' : '' }}">
    <i class="bi bi-people-fill"></i> Ressources
  </a>

  <div class="nav-section">Équipements</div>
  <a href="{{ route('equipements.index') }}" class="nav-link {{ request()->routeIs('equipements.*') ? 'active' : '' }}">
    <i class="bi bi-laptop"></i> Inventaire
  </a>
  <a href="{{ route('attributions.index') }}" class="nav-link {{ request()->routeIs('attributions.*') ? 'active' : '' }}">
    <i class="bi bi-box-arrow-in-right"></i> Attributions
  </a>
  <a href="{{ route('restitutions.index') }}" class="nav-link {{ request()->routeIs('restitutions.*') ? 'active' : '' }}">
    <i class="bi bi-box-arrow-left"></i> Restitutions
  </a>
  <a href="{{ route('stocks.index') }}" class="nav-link {{ request()->routeIs('stocks.*') ? 'active' : '' }}">
    <i class="bi bi-archive"></i> Mouvements stock
  </a>

  <div class="nav-section">Sécurité</div>
  <a href="{{ route('cameras.index') }}" class="nav-link {{ request()->routeIs('cameras.*') ? 'active' : '' }}">
    <i class="bi bi-camera-video-fill"></i> Caméras
  </a>
  <a href="{{ route('alarmes.index') }}" class="nav-link {{ request()->routeIs('alarmes.*') ? 'active' : '' }}">
    <i class="bi bi-shield-fill-check"></i> Alarmes
  </a>

  <div class="nav-section">Réseau</div>
  <a href="{{ route('acces-wifis.index') }}" class="nav-link {{ request()->routeIs('acces-wifis.*') ? 'active' : '' }}">
    <i class="bi bi-wifi"></i> Accès WIFI
  </a>

  <div class="nav-section">Support</div>
  <a href="{{ route('interventions.index') }}" class="nav-link {{ request()->routeIs('interventions.*') ? 'active' : '' }}">
    <i class="bi bi-tools"></i> Interventions
  </a>

  <div class="nav-section">Rapports PDF</div>
  <a href="{{ route('pdf.inventaire') }}" class="nav-link" target="_blank">
    <i class="bi bi-file-earmark-pdf"></i> Inventaire PDF
  </a>
  <a href="{{ route('pdf.ressources') }}" class="nav-link" target="_blank">
    <i class="bi bi-file-earmark-person"></i> Ressources PDF
  </a>
  <a href="{{ route('pdf.interventions') }}" class="nav-link" target="_blank">
    <i class="bi bi-file-earmark-text"></i> Interventions PDF
  </a>

  <div class="mt-3 p-3" style="border-top:1px solid rgba(255,255,255,.1)">
    <div style="color:rgba(255,255,255,.6); font-size:12px;">
      <i class="bi bi-person-circle me-1"></i> {{ auth()->user()->name }}
    </div>
    <form method="POST" action="{{ route('logout') }}" class="mt-2">
      @csrf
      <button type="submit" class="btn btn-sm btn-outline-light w-100" style="font-size:12px;">
        <i class="bi bi-box-arrow-right me-1"></i> Déconnexion
      </button>
    </form>
  </div>
</div>

<!-- Main content -->
<div class="main-content">
  <div class="topbar">
    <h6 class="page-title">@yield('title')</h6>
    <div class="d-flex align-items-center gap-3">
      <span class="badge bg-primary">{{ now()->format('d/m/Y') }}</span>
      <span class="text-muted" style="font-size:12px;">
        <i class="bi bi-geo-alt me-1"></i> {{ auth()->user()->name }}
      </span>
    </div>
  </div>

  <div class="content-area">
    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    @endif
    @if(session('error'))
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    @endif
    @yield('content')
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>